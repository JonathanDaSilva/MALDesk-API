# -*- mode: ruby -*-
# vi: set ft=ruby :

project_name = "maldesk"
ip_address   = "172.22.22.22"

Vagrant.configure(2) do |config|
  config.vm.box = "chef/debian-7.4"

  config.vm.synced_folder ".", "/vagrant/", create: true

  config.hostmanager.enabled = true
  config.hostmanager.manage_host = true
  config.vm.define project_name do |node|
    node.vm.hostname = project_name + ".local"
    node.vm.network :private_network, ip: ip_address
    node.hostmanager.aliases = [ "www." + project_name + ".com" ]
  end
  config.vm.provision :hostmanager

  config.vm.provision "shell", inline: <<-shell
    wget -O - http://dl.hhvm.com/conf/hhvm.gpg.key | sudo apt-key add -
    echo deb http://dl.hhvm.com/debian wheezy main | sudo tee /etc/apt/sources.list.d/hhvm.list
    sudo apt-get update
    sudo apt-get install --force-yes -y hhvm nginx dos2unix
    sudo /usr/share/hhvm/install_fastcgi.sh
    sudo ln -s --force `which hhvm` /usr/local/bin/php
    sudo cp /vagrant/server /etc/nginx/sites-available/default
    sudo dos2unix /etc/nginx/sites-available/default
    sudo service nginx stop
    sudo service nginx start
    sudo service hhvm stop
    sudo service hhvm start
  shell

end
