all:
	@echo "No default target. Use:"
	@echo "  \"make clean\" to delete test results."
	@echo "  \"make docs\" to generate documentation."
	@echo "  \"make test\" to run the test suite."
	@echo "  \"make purge\" to remove the composer files."

# Simple cleaning: delete locally generated files.
clean:  
	find . \( -name php_errors.log -o -name "*.dot" -o -name "*.svg" \) -delete
	rm -fr doxygen 

docs:
	doxygen Grafizzi.dox

test:
	phpunit -v Grafizzi

# Stronger cleaning: will need net access to restore.
purge: clean
	rm -fr composer.lock composer.phar vendor

