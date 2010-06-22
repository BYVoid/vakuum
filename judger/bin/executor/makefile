CC=gcc
EXE=executor
CFLAGS=-lm -I/usr/include/libxml2 -lxml2 -g
SOURCES:=$(wildcard *.c)
HEADERS:=$(wildcard *.h)

executor:$(SOURCES) $(HEADERS)
	$(CC) $(SOURCES) $(CFLAGS) -o $(EXE)
	$(CC) $(SOURCES) $(CFLAGS) -DENABLE_LOG -o $(EXE)_dev
