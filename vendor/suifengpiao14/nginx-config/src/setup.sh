#!/bin/sh
# 第一个参数为项目路径,默认为当前目录，第二个参数为域名，默认为项目路径文件名
root_dir=`pwd`
document_root=${1:-"${root_dir}/public"}
domain=${2:-` basename $root_dir`}

config_file="${domain}.conf";

if [ -e ${config_file} ];then
	echo "nginx config  is already exists!"
	exit 0;
fi

cp ./vendor/suifengpiao14/nginx-config/src/domain.conf ${config_file}

#替换模板变量
sed -i "s#{document_root}#${document_root}#g" ${config_file}
sed -i "s#{domain}#${domain}#g" ${config_file}

echo "nginx config is installed!"

exit 0