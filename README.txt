to get this working:

Install Homestead/Vagrant.

I added the homestead.yaml to use (also make sure this repo is in C:\Users\{YOUR_PC_NAME}\code\thesis for this yaml to work as is)
Replace the Homestead.yaml file to whereever you installed the Homestead directory during the installation (probably C:\Users\{YOUR_PC_NAME})


Also go to your hosts file (C:\Windows\System32\drivers\etc) and add this line:

192.168.10.10		thesis.test

cd to the Homestead directory and vagrant up --provision
once you are in, SSH into the box (vagrant ssh) and go to the project home directory( cd code/thesis) and type in:

composer install


after this everything should work fine

Endpoints are as follows:

thesis.test/api/create
    Creates Schedule and appointment (if schedule exists, adds to array)
        Required parameters:
            start_time
            end_time
            schedule

thesis.test/api/getSchedule
    Returns schedule with all appointments
        Required parameters:
            schedule

thesis.test/api/getAppointment
    Returns appointment from selected schedule
        Required parameters:
            schedule
            id (the id of the appointment itself)

thesis.test/api/deleteSchedule
    Deletes schedule and all assocaited appointments
        Required parameters:
            schedule

thesis.test/api/deleteAppointment
    Deletes appointment from selected schedule
        Required parameters:
            schedule
            id (the id of the appointment itself)

            
if you have any questions please let me know!