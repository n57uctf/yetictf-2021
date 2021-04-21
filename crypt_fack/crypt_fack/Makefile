FLAGS := -Wall
CC := gcc

SOURCES := src/server.c \
           src/helpers.c \
           src/client.c \
           src/db_api.c \
           src/crypt.c \
           src/b64/base64.c \

LIBS := -lpthread \
        -lrt

MYSQL := `mysql_config --cflags --libs`

.PHONY: all

all: $(SOURCES)
	$(CC) $(SOURCES) -o server $(LIBS) $(FLAGS) $(MYSQL)

start_server: $(SOURCES)
	$(CC) $(SOURCES) -o server $(LIBS) $(FLAGS) $(MYSQL)
	./server
