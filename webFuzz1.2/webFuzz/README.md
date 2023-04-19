# webFuzz

A grey-box fuzzer for web applications

## Installation

1. Instrument your web application using [php-ast-instrumentation](https://bitbucket.org/srecgrp/hhvm-fuzzing/src/master/ast/php/).
2. Make sure your instrumented web application now works fine.
3. Copy `instr.meta` file from the instrumentation output, to the same directory where file `curses_runner.py` resides.
4. Install the python dependencies:  ```pip3 install --upgrade -r /hhvm-fuzzing/web_fuzzer/requirements.txt```
5. Install cowsay with ```sudo apt-get install -y cowsay```

## Usage

Run the fuzzer using `./webFuzz.py`.

Example run: ```./webFuzz.py -v -v --driver webFuzz/drivers/chromedriver86 -m instr.meta -w 3 -b 'wp-login|action|logout' -s -r auto 'http://localhost/wp-admin/'```

```
python webFuzz.py -w 8 --meta /home/wh/Desktop/wordpress/instr.meta --driver webFuzz/drivers/geckodriver -vv -p -s -r simple 'http://192.168.30.137/wordpress_instr1/wp-login.php'
```

## TODO
*  Manual execution -> MARCOS
*  Fix the curses interface (arrow keys) -> MARCOS
*  Implement some kind of nonce field detection (crawler treats identical links with different nonces as unique and falls in infinite loop)
*  reward mutation functions that succeed in finding bugs
*  pausing the fuzzer causes the response time timer for a request to keep counting. find a way to fix it
*  report requests that have long response times (and cpu usage maybe)
*  write more tests, and simplify them too
*  implement stored xss detection
*  it would be a good idea not to completely throw away mutated nodes that didn't make it to the heap 
*  Implement the following  mutating functions:
   * remove some parameters from url

## Trophy Case
* OSCommerce CE-Phoenix - 8 Zero day XSS bugs - [GitHub Issue](https://github.com/gburton/CE-Phoenix/issues/1039)

## Authors

* **Orpheas van Rooij** - *ovan-r01@cs.ucy.ac.cy*
* **Marcos Antonios Charalambous** - *mchara01@cs.ucy.ac.cy*
* **Demetris Kaizer** - *dkaize01@cs.ucy.ac.cy*

## License
[GNU GPLv3](https://choosealicense.com/licenses/gpl-3.0/)
