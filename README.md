# Avatar package

This package exposes user avatars via a simpel URL scheme such as `/avatar/sandropadin.jpg`

## Usage

### Simple

  __GET__ - /avatar/{username}.{format}

  __GET__ - /avatar/{userId}.{format}

  _Returns_ - a 50x50 image for the specified user of the specified format. Possible formats: jpg|png|gif.

### Still simple

  __GET__ - /avatar/WxH/{username}.{format}

  __GET__ - /avatar/WxH/{userId}.{format}

  _Returns_ - just as above, but with the specified dimensions.
