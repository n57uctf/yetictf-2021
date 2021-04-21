#include <stdio.h>

#define LENGTH 32

int main(int argc, char **argv)
{
	char flag[LENGTH + 1] = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\0";
	for (int i = 0; i < LENGTH; ++i)
		if ((flag[i] | 254) == 254) 
			++flag[i];
		else --flag[i];
	char encflag[LENGTH + 1] = "________________________________\0";
	printf("%s\n", encflag);
	return 0;
}
