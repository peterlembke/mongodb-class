# PHP Info

On the phpinfo page you see what PHP extensions are loaded and what versions are used and all settings used.

You can check with phpinfo if an ini file have been used and if an extension is activated.

Create a phpinfo.php file in a public folder on your web server. With this content
```
<?php
phpinfo(); 
```

Surf to phpinfo.php and you should see the page.

You can now search for mongo and see if there is a section for that and if the ini file exist in the list at the top of the page.

# License
This documentation is copyright (C) 2021 Peter Lembke.  
Permission is granted to copy, distribute and/or modify this document under the terms of the GNU Free Documentation License, Version 1.3 or any later version published by the Free Software Foundation; with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.  
You should have received a copy of the GNU Free Documentation License along with this documentation. If not, see [https://www.gnu.org/licenses/](https://www.gnu.org/licenses/).  SPDX-License-Identifier: GFDL-1.3-or-later  
