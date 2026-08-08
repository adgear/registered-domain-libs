CC=gcc
CFLAGS=-g -O2 -Wall -Werror -Wextra -Wstrict-prototypes -Wmissing-prototypes -Wpointer-arith -Wreturn-type -Wcast-qual -Wswitch -Wshadow -Wcast-align -Wno-unused-parameter -Wchar-subscripts -Winline -Wnested-externs -Wredundant-decls
SOURCES=test-dkim-regdom.c dkim-regdom.c
EXECUTABLE=test-dkim-regdom
OBJECTS=dkim-regdom.o
LIBRARY=libregdom.so
PREFIX=/usr/local

UNAME_S := $(shell uname -s)

ifeq ($(UNAME_S),Darwin)
	# Apple's ld has no -soname (that's a GNU ld/ELF concept). The macOS
	# equivalent of "the name other binaries should look this library up by"
	# is -install_name, baked into the dylib's own LC_ID_DYLIB load command.
	# Without it, the linker defaults to the bare filename "libregdom.so",
	# which dyld can't resolve at runtime for any consumer (e.g.
	# domain-classifier) unless it happens to sit next to the binary or
	# DYLD_LIBRARY_PATH is set - and DYLD_LIBRARY_PATH doesn't survive
	# exec() into a shell-script wrapper such as Erlang's bin/erl. Point it
	# at the absolute path this Makefile installs to instead.
	SHARED_FLAGS = -dynamiclib -install_name $(PREFIX)/lib/$(LIBRARY)
else
	SHARED_FLAGS = -shared
endif

all: $(EXECUTABLE) $(LIBRARY)

clean:
	rm -f $(EXECUTABLE)
	rm -f $(LIBRARY)
	rm -f $(OBJECTS)

install: libregdom.so
	cp libregdom.so $(PREFIX)/lib
	cp dkim-regdom.h $(PREFIX)/include

$(EXECUTABLE): $(SOURCES)
	$(CC) $(CFLAGS) -o $@ $(SOURCES) $(LDFLAGS)

dkim-regdom.o: dkim-regdom.c dkim-regdom.h
	$(CC) $(CFLAGS) -fPIC -o dkim-regdom.o -c dkim-regdom.c

$(LIBRARY): $(OBJECTS)
	$(CC) $(SHARED_FLAGS) -o $@ $(OBJECTS)
