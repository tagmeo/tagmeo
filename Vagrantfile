# -*- mode: ruby -*-
# vi: set ft=ruby :

github_username = "fideloper"
github_repo = "Vaprobash"
github_branch = "1.4.2"
github_url = "https://raw.githubusercontent.com/#{github_username}/#{github_repo}/#{github_branch}"
github_pat = "e835c98b3a2cd9bd12e3877378f1a295e99ded95"

hostname = "tagmeo.dev"

server_ip = "192.168.22.50"
server_cpus = "1"
server_memory = "512"
server_swap = "768"
server_timezone  = "UTC"

mysql_version = "5.6"
mysql_root_password = "secret"
mysql_enable_remote = "false"

php_timezone = "UTC"
php_version = "5.6"

ruby_version = "latest"
ruby_gems = []

hhvm = "false"

composer_packages = []

public_folder = "/vagrant/public"

nodejs_version = "latest"
nodejs_packages = [
  "babel",
  "bower",
  "coffee-script",
  "gulp",
  "jshint",
  "leasot",
  "marked",
  "node-gyp"
]

Vagrant.configure("2") do |config|
    config.vm.box = "ubuntu/trusty64"
    config.vm.define "Vaprobash" do |vapro|
  end

  if Vagrant.has_plugin?("vagrant-hostmanager")
    config.hostmanager.enabled = true
    config.hostmanager.manage_host = true
    config.hostmanager.ignore_private_ip = false
    config.hostmanager.include_offline = false
  end

  config.vm.hostname = hostname

  if Vagrant.has_plugin?("vagrant-auto_network")
    config.vm.network :private_network, :ip => "0.0.0.0", :auto_network => true
  else
    config.vm.network :private_network, ip: server_ip
    config.vm.network :forwarded_port, guest: 80, host: 8000
  end

  config.ssh.forward_agent = true

  config.vm.synced_folder ".", "/vagrant",
  id: "core",
  :nfs => true,
  :mount_options => ['nolock,vers=3,udp,noatime,actimeo=2,fsc']

  if File.file?(File.expand_path("~/.gitconfig"))
    config.vm.provision "file", source: "~/.gitconfig", destination: ".gitconfig"
  end

  config.vm.provider :virtualbox do |vb|
    vb.name = hostname
    vb.customize ["modifyvm", :id, "--cpus", server_cpus]
    vb.customize ["modifyvm", :id, "--memory", server_memory]
    vb.customize ["guestproperty", "set", :id, "/VirtualBox/GuestAdd/VBoxService/--timesync-set-threshold", 10000]
    vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    vb.customize ["modifyvm", :id, "--natdnsproxy1", "on"]
  end

  config.vm.provider "vmware_fusion" do |vb, override|
    override.vm.box_url = "http://files.vagrantup.com/precise64_vmware.box"
    vb.vmx["memsize"] = server_memory
  end

  if Vagrant.has_plugin?("vagrant-cachier")
    config.cache.scope = :box
    config.cache.synced_folder_opts = {
      type: :nfs,
      mount_options: ['rw', 'vers=3', 'tcp', 'nolock']
    }
  end

  config.vm.provision "shell", path: "#{github_url}/scripts/base.sh", args: [github_url, server_swap, server_timezone]
  config.vm.provision "shell", path: "#{github_url}/scripts/base_box_optimizations.sh", privileged: true
  config.vm.provision "shell", path: "#{github_url}/scripts/php.sh", args: [php_timezone, hhvm, php_version]
  config.vm.provision "shell", path: "#{github_url}/scripts/nginx.sh", args: [server_ip, public_folder, hostname, github_url]
  config.vm.provision "shell", path: "#{github_url}/scripts/mariadb.sh", args: [mysql_root_password, mysql_enable_remote]
  config.vm.provision "shell", path: "#{github_url}/scripts/redis.sh"
  config.vm.provision "shell", path: "#{github_url}/scripts/nodejs.sh", privileged: false, args: nodejs_packages.unshift(nodejs_version, github_url)
  config.vm.provision "shell", path: "#{github_url}/scripts/composer.sh", privileged: false, args: [github_pat, composer_packages.join(" ")]
  config.vm.provision "shell", path: "./scripts/create-database.sh"
end
