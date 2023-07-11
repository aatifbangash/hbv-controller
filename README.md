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

Futher check this link https://tommoa.me/blog/github-auto-update-submodules/
