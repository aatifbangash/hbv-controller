Following (hbv2-controller) is the parent repo where other repos are registered as a submodule. I have defined the workflow in it which i use to capture the event from the submodule whenever any change happend and pushed in the submodule repo. 
Following is the workflow code.

name: Dispatch Event from Submodule
```
on:
  repository_dispatch:
    types: [hello_world] ### name of the event trigger from the submodules

jobs:
  dispatch:
    runs-on: ubuntu-latest

    steps:
      - name: Print message
        run: |
          echo "HELLO WORLD"
          echo ${{ github.event.client_payload.microservice_name }} ### payload from the submodule event
```

Further check this link https://tommoa.me/blog/github-auto-update-submodules/

### Microservices FLow using submodules and copilot
* All the microservices repos will be registered as a submodule in the main repo.
* Whenever any change happens and is pushed to the submodule. That will trigger an event to the main repo.
* The github action in the main repo will get the microservice name (which has been changed) from the payload and fetch the latest content of the submodule via GIT SUBMODULE UPDATE command
* Next, will run the copilot command to create the docker image and push that image to ECR and spin up or re-deploy the ECS service with latest contents/code.
