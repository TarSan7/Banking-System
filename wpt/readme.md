# To run Webpagetest
1. Go to the directory wpt/server and run
```
    $ docker build -t local-wptserver .
```
2. Then go to the directory wpt/agent and run:
```
    $ chmod u+x script.sh
    $ docker build -t local-wptagent .
    $ docker run -d -p 4000:80 local-wptserver
    $ docker run -p 4001:80 --network="host" -e "SERVER_URL=http://localhost:4000/work/" -e "LOCATION=Test" local-wptagent
```