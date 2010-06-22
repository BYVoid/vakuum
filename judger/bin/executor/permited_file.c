#include "executor.h"

#define PFCNT 1024
char *permited_file[PFCNT];
int permited_file_cnt;

void permited_file_add(const char *value)
{
	int lenv = strlen(value);
	permited_file[permited_file_cnt] = malloc((lenv+1)*sizeof(char));
	strcpy(permited_file[permited_file_cnt],value);
	permited_file_cnt++;
}

int permited_file_check(const char *value)
{
	int i;
	for (i=0;i<permited_file_cnt;i++)
	{
		if (strcmp(value,permited_file[i]) == 0)
			return 1;
	}
	return 0;
}
