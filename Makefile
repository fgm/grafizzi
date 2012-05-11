all: docs

clean:  
	find . \( -name php_errors.log -o -name "*.dot" -o -name "*.svg" \) -delete
	rm -fr doxygen

docs:
	doxygen Grafizzi.dox

