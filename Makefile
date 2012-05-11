all: docs

# Simple cleaning: delete locally generated files.
clean:  
	find . \( -name php_errors.log -o -name "*.dot" -o -name "*.svg" \) -delete
	rm -fr doxygen 

# Stronger cleaning: will need net access to restore.
purge: clean
	rm -fr composer.lock vendor

docs:
	doxygen Grafizzi.dox

