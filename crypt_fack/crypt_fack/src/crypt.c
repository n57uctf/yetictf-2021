#include <stdio.h>
#include <sys/mman.h>                                                           
#include <sys/types.h>                                                          
#include <sys/stat.h>                                                           
#include <fcntl.h>                                                              
#include <unistd.h>
#include <string.h>

#include "crypt.h"

static struct instruction_t PROGRAM[PROGRAM_SIZE];
static unsigned short STACK[STACK_SIZE];
static unsigned int SP = 0;

static char * bf_magic = ">+++++++[<++++++++++++>-]<+>>++++++++[<+++++++++>-]++++[>++++<-]>+[<++++++++>-]<+>++++++[>++++++<-]>++[<++++++>-]<+>>++++++++[<+++++++++>-]++++[>++++<-]>+[<++++++++>-]<+>+++++[>+++++<-]>[<+++++>-]+++++[>++++++<-]>+[<++++++++>-]++++[>++++<-]>+[<++++++++>-]<+>+++++[>++++++<-]>[<++++++++>-]++++[>++++<-]>+[<++++++++>-]>+++++++[<++++++++++>-]<->+++++[>+++++++<-]>[<+++++++>-]<->>++++++++[<+++++++++>-]++++[>++++<-]>+[<++++++++>-]<+++>>+++++++[<++++++++++>-]<->+++++[>++++++<-]>+[<++++++++>-]>+++[<+++++>-]+++++[>+++++<-]>+[<+++++++>-]>>+++++[<++++++++++>-]>+++++++[<++++++++++>-]<->+++[>+++++++++<-]>[<+++++++++>-]<+>++++[>++++<-]>+[<++++++++>-]<+>++++[>++++++<-]>[<++++++++>-]<++>>++++++++[<+++++++++>-]++++[>+++++<-]>[<+++++++>-]<->>+++++++[<++++++++++>-]<->+++++[>++++++<-]>+[<++++++++>-]++++[>++++<-]>+[<++++++++>-]>++++[<++++>-]++++[>++++++<-]>[<++++++>-]+++++[>++++++<-]>+[<+++>-]++++[>+++++++<-]>[<+++++++>-]<-";

int crypt_bf_compile(char *str)
{
  unsigned short pc = 0, jmp_pc;
  char c;

  while ((c = *(str++)) != 0 && pc < PROGRAM_SIZE)
  {
    switch (c)
    {
      case '>': PROGRAM[pc].operator = OP_INC_DP; break;
      case '<': PROGRAM[pc].operator = OP_DEC_DP; break;
      case '+': PROGRAM[pc].operator = OP_INC_VAL; break;
      case '-': PROGRAM[pc].operator = OP_DEC_VAL; break;
      case '.': PROGRAM[pc].operator = OP_OUT; break;
      case ',': PROGRAM[pc].operator = OP_IN; break;
      case '[':
        PROGRAM[pc].operator = OP_JMP_FWD;
        if (STACK_FULL())
        {
          return -1;
        }
        STACK_PUSH(pc);
        break;

      case ']':
        if (STACK_EMPTY())
        {
          return -1;
        }
        jmp_pc = STACK_POP();
        PROGRAM[pc].operator =  OP_JMP_BCK;
        PROGRAM[pc].operand = jmp_pc;
        PROGRAM[jmp_pc].operand = pc;
        break;

      default: pc--; break;
    }
    pc++;
  }

  if (!STACK_EMPTY() || pc == PROGRAM_SIZE)
  {
    return -1;
  }

  PROGRAM[pc].operator = OP_END;
  return 0;
}

int crypt_bf_execute(unsigned char * data)
{
  unsigned short pc = 0;
  int ptr = 0;

  while (PROGRAM[pc].operator != OP_END && ptr < DATA_SIZE)
  {
    switch (PROGRAM[pc].operator)
    {
      case OP_INC_DP: ptr++; break;
      case OP_DEC_DP: ptr--; break;
      case OP_INC_VAL: data[ptr]++; break;
      case OP_DEC_VAL: data[ptr]--; break;
      case OP_OUT: putchar(data[ptr]); break;
      case OP_IN: data[ptr] = (unsigned int)getchar(); break;
      case OP_JMP_FWD: if(!data[ptr]) { pc = PROGRAM[pc].operand; } break;
      case OP_JMP_BCK: if(data[ptr]) { pc = PROGRAM[pc].operand; } break;
      default: return -1;
    }
    pc++;
  }

  if (ptr >= DATA_SIZE)
  {
    return -1;
  }

  return 0;
}

int crypt_encrypt_data(char * in_data)
{
  char out_data[DATA_SIZE] = "";
  unsigned char magic_data[DATA_SIZE] = "";
  void * mem = NULL;
  unsigned int len = 0;

  memset(PROGRAM, 0, sizeof(PROGRAM));

  len = b64_decode((unsigned char *)in_data, strlen(in_data),
      (unsigned char *)out_data);

  if (0 == len) return -1;

  if (-1 == crypt_bf_compile(bf_magic))
  {
    return -1;
  }
  if (-1 == crypt_bf_execute(magic_data))
  {
    return -1;
  }

  mem = mmap(NULL, DATA_SIZE, PROT_WRITE | PROT_EXEC,
      MAP_ANON | MAP_PRIVATE, -1, 0);

  if (MAP_FAILED == mem) return -1; 

  memcpy(mem, magic_data, DATA_SIZE);

  void (* magic) (char *, char) = mem;
  
  for (int i = 0; i < len; ++i)
  {
    magic(&out_data[i], 0x77);
  }

  munmap(mem, DATA_SIZE);
  memset(in_data, 0, DATA_SIZE);

  len = b64_encode((unsigned char *)out_data, len,
      (unsigned char *)in_data);

  return 0;
}

int crypt_decrypt_data(char * in_data)
{
  char out_data[DATA_SIZE] = "";
  unsigned char magic_data[DATA_SIZE] = "";
  void * mem = NULL;
  unsigned int len = 0;

  memset(PROGRAM, 0, sizeof(PROGRAM));

  len = b64_decode((unsigned char *)in_data, strlen(in_data),
      (unsigned char *)out_data);

  if (0 == len) return -1;

  if (-1 == crypt_bf_compile(bf_magic))
  {
    return -1;
  }
  if (-1 == crypt_bf_execute(magic_data))
  {
    return -1;
  }

  mem = mmap(NULL, DATA_SIZE, PROT_WRITE | PROT_EXEC,
      MAP_ANON | MAP_PRIVATE, -1, 0);

  if (MAP_FAILED == mem) return -1; 

  memcpy(mem, magic_data, DATA_SIZE);

  void (* magic) (char *, char) = mem;
  
  for (int i = 0; i < len; ++i)
  {
    magic(&out_data[i], 0x77);
  }

  munmap(mem, DATA_SIZE);
  memset(in_data, 0, DATA_SIZE);

  len = b64_encode((unsigned char *)out_data, len,
      (unsigned char *)in_data);

  return 0;
}
