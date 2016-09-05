# Micro app for cycling routes
Purpose of this app is to provide list and description of cycling routes defined by GPX file. Currently you 
have to create folder for each route in ``data`` dir and add one GPX file along with README.md, which will serve as 
description. You may also add custom name by name.txt and any number of images with .jpg extension.

## Data structure
```
data/
  - route_1/
         - name.txt
         - route.gpx
         - README.md
         - image1.jpg
         - image2.jpg
  - route_2/
         - name.txt
         - route with any file name.gpx
         - README.md
         - any.jpg
         - something.jpg
```         
## Installation
  1. git clone
  2. composer install
  3. add data
