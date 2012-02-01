# Avatar package

This package exposes user avatars via a simpel URL scheme such
as `/avatar/sandropadin.jpg`

## Installation

Download a zip of this project. Copy the folder to the `packages`
folder of your Concrete5 installation. Make sure it is named `avatar`. Go
to the dashboard under 'Add Functionality' and install this package.

## Usage

### Simple

  __GET__ - /avatar/{username}.{format}

  __GET__ - /avatar/{userId}.{format}

  _Returns_ - a 50x50 image for the specified user of the specified format. Possible formats: jpg|png|gif.

### Still simple

  __GET__ - /avatar/WxH/{username}.{format}

  __GET__ - /avatar/WxH/{userId}.{format}

  _Returns_ - just as above, but with the specified dimensions.
