# Avatar package

This package exposes user avatars via a simple URL scheme such
as `/avatar/sandropadin.jpg`

## Installation

0. Download a zip of this project.
0. Copy the folder to the `packages` folder of your Concrete5 installation.
0. Make sure the folder is named `avatar`.
0. Go to the dashboard under 'Add Functionality' and install the avatar package.

## Usage

### Simple

  __GET__ - /avatar/{username}.{format}

  __GET__ - /avatar/{userId}.{format}

  _Returns_ - a 50x50 image for the specified user of the specified format. Possible formats: jpg|png|gif.

### Still simple

  __GET__ - /avatar/WxH/{username}.{format}

  __GET__ - /avatar/WxH/{userId}.{format}

  _Returns_ - just as above, but with the specified dimensions.


## Caveats

The `/files/cache` directory must be writeable.

## Credits

Uses the [PHPThumb Library](https://github.com/masterexploder/PHPThumb) to generate the thumbnails.