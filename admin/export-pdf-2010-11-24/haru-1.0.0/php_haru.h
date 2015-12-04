/*
  +----------------------------------------------------------------------+
  | PHP Version 5                                                        |
  +----------------------------------------------------------------------+
  | Copyright (c) 1997-2008 The PHP Group                                |
  +----------------------------------------------------------------------+
  | This source file is subject to version 3.01 of the PHP license,      |
  | that is bundled with this package in the file LICENSE, and is        |
  | available through the world-wide-web at the following url:           |
  | http://www.php.net/license/3_01.txt                                  |
  | If you did not receive a copy of the PHP license and are unable to   |
  | obtain it through the world-wide-web, please send a note to          |
  | license@php.net so we can mail you a copy immediately.               |
  +----------------------------------------------------------------------+
  | Author: Antony Dovgal <tony2001@php.net>                             |
  +----------------------------------------------------------------------+
*/

/* $Id: php_haru.h,v 1.4 2008/11/19 08:47:42 tony2001 Exp $ */

#ifndef PHP_HARU_H
#define PHP_HARU_H

extern zend_module_entry haru_module_entry;
#define phpext_haru_ptr &haru_module_entry

#define PHP_HARU_VERSION "1.0.0"

#ifdef ZTS
#include "TSRM.h"
#endif

PHP_MINIT_FUNCTION(haru);
PHP_MSHUTDOWN_FUNCTION(haru);
PHP_MINFO_FUNCTION(haru);

#endif	/* PHP_HARU_H */

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: noet sw=4 ts=4 fdm=marker
 * vim<600: noet sw=4 ts=4
 */
