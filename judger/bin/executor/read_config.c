#include "executor.h"
#include <libxml/xmlmemory.h>
#include <libxml/parser.h>

int stringToInt(const char *str)
{
	int num;
	sscanf(str,"%d",&num);
	return num;
}

int set_option(const xmlChar *key,const char *value)
{
	if (xmlStrcmp(key,(const xmlChar *)"program") == 0)
	{
		strcpy(program,value);
	}
	else if (xmlStrcmp(key,(const xmlChar *)"time_limit") == 0)
	{
		time_limit = stringToInt(value);
	}
	else if (xmlStrcmp(key,(const xmlChar *)"memory_limit") == 0)
	{
		memory_limit = stringToInt(value);
	}
	else if (xmlStrcmp(key,(const xmlChar *)"output_limit") == 0)
	{
		output_limit = stringToInt(value);
	}
	else if (xmlStrcmp(key,(const xmlChar *)"file") == 0)
	{
		permited_file_add(value);
	}
	else if (xmlStrcmp(key,(const xmlChar *)"syscall") == 0)
	{
		forbidden_syscall_add(stringToInt(value));
	}
	else
	{
		//Unknown label
		return 1;
	}
	return 0;
}

int read_config(char *config_file)
{
	xmlDocPtr doc;
	xmlNodePtr cur;
	xmlChar *value;

	doc = xmlParseFile(config_file);
	if (doc == NULL)
	{
		return 1;
	}
	
	cur = xmlDocGetRootElement(doc);
	
	if (xmlStrcmp(cur->name,(const xmlChar *)"document") != 0)
	{
		xmlFreeDoc(doc);
		return 1;
	}
	
	for (cur = cur->xmlChildrenNode;cur != NULL;cur = cur->next)
	{
		if (cur->type == 1)
		{
			value = xmlNodeListGetString(doc,cur->xmlChildrenNode, 1);
			set_option(cur->name,(const char *)value);
			xmlFree(value);
		}
	}
	
	xmlFreeDoc(doc);
	return 0;
}