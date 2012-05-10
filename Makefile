all: docs

clean:  
	find . -name php_errors.log -delete
	rm -fr doxygen

docs:
	doxygen Grafizzi.dox

