This was a small project I did for vanarts in my free time. It works with wordpress in 2 ways: 
1- as an seperate external file, loads wp object,
2- a small plugin to take action while publishing post, this will also publish a slideshow image, also create a config file which will use by automated task to detect absolute path

This project was intented to automate the process of upload and post each student projects with their screenshots on wordpress. Code would run as cron job and what admin will do is just checking if there is a new student project and just click to publish them.


This code will search all server folders and subfolders look for a spesific xml file which contains student's projects, then parse these files. It will check for each project file if that project already exists, if not it will populate a new wordpress post, category and meta data and put them in pending status. Once admin logs in and publish a post related image for slideshow will be also published
