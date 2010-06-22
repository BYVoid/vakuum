#include "executor.h"

FILE *fplog;
char logbuf[LOGBUFF_SIZE];

void writelog(const char *message)
{
	if (fplog == NULL)
		fplog = fopen("executor.log","w");
	fprintf(fplog,"%s",message);
}

