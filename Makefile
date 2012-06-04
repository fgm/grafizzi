#
# Makefile: a component of the Grafizzi library.
# 
# (c) 2012 Frédéric G. MARAND <fgm@osinet.fr>
# 
# Grafizzi is free software: you can redistribute it and/or modify it under the
# terms of the GNU Lesser General Public License as published by the Free
# Software Foundation, either version 3 of the License, or (at your option) any
# later version.
# 
# Grafizzi is distributed in the hope that it will be useful, but WITHOUT ANY
# WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
# A PARTICULAR PURPOSE.  See the GNU Lesser General Public License for more
# details.
# 
# You should have received a copy of the GNU Lesser General Public License
# along with Grafizzi, in the COPYING.LESSER.txt file.  If not, see
# <http://www.gnu.org/licenses/>
# 

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

