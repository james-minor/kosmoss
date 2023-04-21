# Kosmoss CMS

A custom, lightweight, PHP-based **Content Management System**.

## Deployment

Kosmoss is designed to easily run and deploy on any LAMP stack. Below are a list of guides
for running Kosmoss on a variety of platforms.

**Note:** These guides presume you have a basic understanding of being an administrator for
various linux machines and at least some experience with server-hosting platforms.

### AWS Lightsail

To deploy Kosmoss on an AWS lightsail instance:
1. Login or sign up for an account on [Amazon Web Services](https://aws.amazon.com/).
2. In the search bar at the top of the screen search for `lightsail`, alternatively you can access the AWS lightsail
   dashboard [here](https://lightsail.aws.amazon.com/).
3. Once at the Lightsail dashboard press **Create instance**.
4. Under **Apps + OS** ensure you have the **LAMP (PHP 8)** instance selected.
5. Select your pricing plan, name your instance, and press **Create instance**.
6. Once your instance is live, SSH into the terminal.
7. Gather your application password for the SQL database by running:
   ```bash
   sudo cat /home/bitnami/bitnami_credentials
   ```
   1. Ensure you have your application credentials written down **somewhere secure**, as you will need if you want the 
      installer to properly write the .env file for you.
8. To run the installer run the following command:
   ```bash
   wget -qO - https://raw.githubusercontent.com/james-minor/kosmoss/master/install/bitnami.bash | bash
   ```
9. To install the composer dependencies run the following commands:
    ```bash
    cd /opt/bitnami/kosmoss
    composer install
    ```
10. You should now have Kosmoss running on an AWS Lightsail LAMP instance!

### Bitnami Virtual Machine

To deploy Kosmoss on a self-hosted Bitnami VM (best for development):
1. Ensure your preferred virtualization software is installed, we recommend [VirtualBox](https://www.virtualbox.org/).
2. Download the `.ova` virtual machine file from [Bitnami](https://bitnami.com/stack/lamp/virtual-machine) and boot up
   a Bitnami virtual machine.
3. Access your Virtual Machine's terminal
4. If you do not know your login credentials for your database:
   1. The default Bitnami username for the SQL database is `root`.
   2. To access your application password, run the following:
      ```bash
      sudo cat /home/bitnami/bitnami_credentials
      ```
   3. Ensure you have your application credentials written down **somewhere secure**, as you will need 
      if you want the installer to properly write the .env file for you.
5. To run the Bitnami installer run the following command:
    ```bash
    wget -qO - https://raw.githubusercontent.com/james-minor/kosmoss/master/install/bitnami.bash | bash
    ```
6. To install the composer dependencies run the following commands:
    ```bash
    cd /opt/bitnami/kosmoss
    composer install
    ```
7. You should now have Kosmoss running on a Bitnami-hosted LAMP stack!