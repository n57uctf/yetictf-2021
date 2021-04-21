#include <stdlib.h>
#include <stdio.h>

#define LENGTH 32

void gen_file(char * path, char * flag)
{
  int offset = 0x870;
  FILE * f = fopen(path, "r+");
	if (NULL == f)
  {
    perror("fopen");
  }

  fseek(f, offset, SEEK_SET);
  for (int i = 0; i < LENGTH; ++i)
  {
    fputc(flag[i],f);
    fseek(f, ++offset, SEEK_SET);
  }

  fputc('\0', f);
  fclose(f);
}
