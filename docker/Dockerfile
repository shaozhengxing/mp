FROM docker.baixing.cn/baixing-php:7.0.9

RUN yum install -y git unzip
RUN yum install -y crontabs
RUN yum update -y nss
RUN yum install -y python-setuptools && wget -O- https://pypi.python.org/packages/44/80/d28047d120bfcc8158b4e41127706731ee6a3419c661e0a858fb0e7c4b2d/supervisor-3.3.0.tar.gz | tar zx -C /tmp;\
cd /tmp/supervisor-3.3.0/ && python setup.py install && rm -rf /tmp/supervisor

ENV PATH /home/php/bin:/home/php/sbin:/home/tengine/sbin:$PATH
RUN curl -sS https://getcomposer.org/installer | /home/php/bin/php -- --install-dir=/usr/local/bin/ --filename=composer

# copy 项目定制的配置文件
RUN mkdir -p /home/php/etc/php-fpm.d /var/run/php-fpm/
COPY docker/nginx.conf /home/tengine/conf/
COPY docker/php.ini /home/php/etc/php.ini
COPY docker/php-fpm.conf /home/php/etc/php-fpm.conf
COPY docker/fpmpool-lkk.conf /home/php/etc/php-fpm.d/lkk.conf
COPY docker/crond /etc/pam.d/crond

# copy 工具脚本
COPY docker/web.sh /home/

# copy 代码
RUN mkdir /data
COPY . /data/

RUN chown -R daemon.daemon /data/bootstrap/ /data/storage/
#RUN cd /data/ && composer install --no-dev -n
RUN cd /data/ && ln -s .env.pro .env
