sudo yum -y install --nogpgcheck https://dl.fedoraproject.org/pub/epel/epel-release-latest-8.noarch.rpm
sudo yum -y install --nogpgcheck https://download1.rpmfusion.org/free/el/rpmfusion-free-release-8.noarch.rpm 
sudo rpm -ivh https://download1.rpmfusion.org/nonfree/el/rpmfusion-nonfree-release-8.noarch.rpm
sudo yum -y install nginx epel-release screen php-fpm php-common php-cli mariadb mariadb-server 
sudo dnf config-manager --enable powertools
sudo yum -y install ffmpeg ffmpeg-devel
setenforce 0 && sed -i 's/SELINUX=enforcing/SELINUX=permissive/g' /etc/sysconfig/selinux

sudo systemctl enable php-fpm.service
sudo systemctl start php-fpm.service
sudo systemctl status php-fpm.service
cat /etc/nginx/conf.d/php-fpm.conf
ls -l /run/php-fpm/www.sock
cat /etc/nginx/default.d/php.conf
sudo systemctl restart nginx.service
