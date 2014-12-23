# Glide

The purpose of this library is to offer extremely easy image manipulation in a similar fashion to cloud image processing services like [Imgix](http://www.imgix.com/) and [Cloudinary](http://cloudinary.com/).

## Highlights

- Adjust, resize and add effects to images using a simple URL based API.
- Manipulated images are automatically cached and served with far-future expires headers.
- Create your own image processing server or integrate directly into your app.
- Supports the [GD Library](http://php.net/manual/en/book.image.php) and [Imagick PHP extension](http://php.net/manual/en/book.imagick.php).
- Ability to secure image URLs using a private signing key.
- Works with many different file systems, using the [Flysystem](http://flysystem.thephpleague.com/) library.
- Powered by the [Intervention Image](http://image.intervention.io/) image handling and manipulation library.

## The API

### Size

- **Width** `w`
    - The width in pixels of the output image.
    - Example: `image.jpg?w=300`
- **Height** `h`
    - The height in pixels of the output image.
    - Example: `image.jpg?h=300`
- **Fit** `fit`
    - Controls how the output image is fitted to its target dimensions.
    - Accepts: `clip`, `scale`, `crop`
    - Example: `image.jpg?w=300&fit=crop`
- **Crop Position** `crop`
    - Controls how the input image is aligned when the `fit` parameter is set to `crop`.
    - Accepts: `top-left`, `top`, `top-right`, `left`, `center`, `right`, `bottom-left`, `bottom`, `bottom-right`
    - Default is `center`.
    - Example: `image.jpg?crop=top-left`
- **Rectangle** `rect`
    - Crops an image to specific dimensions prior to any other resize operation.
    - Accepts format: `width,height,x,y`
    - Example: `image.jpg?rect=100,100,25,90`
- **Orientation** `ori`
    - Rotates an image by supplied angle.
    - Accepts: `auto`, `0`, `90`, `180`, `270`
    - By default it uses Exif data to automatically orient images correctly (`auto`).
    - Example: `image.jpg?ori=90`

### Adjustments

- **Brightness** `bri`
    - Adjusts the image brightness.
    - Use values between `-100` and `+100`.
    - Example: `image.jpg?bri=50`
- **Contrast** `con`
    - Adjusts the image contrast.
    - Use values between `-100` for min. contrast, `0` for no change and `+100` for max. contrast.
    - Example: `image.jpg?con=50`
- **Gamma** `gam`
    - Adjusts the image gamma.
    - Example: `image.jpg?gam=1.6`
- **Sharpen** `sharp`
    - Sharpen current image with an optional amount.
    - Use values between `0` and `100`.
    - Example: `image.jpg?sharp=15`

### Effects

- **Blur** `blur`
    - Blurs an image by supplied blur strength.
    - Use values between `0` and `100`.
    - Example: `image.jpg?blur=15`
- **Pixelate** `pixel`
    - Applies a pixelation effect to the current image with a given size of pixels.
    - Use values between `0` and `100`.
    - Example: `image.jpg?pixel=12`
- **Filter** `filt`
    - Applies a filter to the image.
    - Accepts: `greyscale`, `sepia`
    - Example: `image.jpg?filt=sepia`

### Output

- **Quality** `q`
    - Define the quality of the encoded image.
    - Use values between `0` and `100`.
    - Defaults to `90`.
    - Example: `image.jpg?blur=15`
- **Format** `fm`
    - Encodes the image to the given format.
    - Accepts: `jpg`, `png`, `gif`.
    - Defaults to `jpg`.
    - Example: `image.jpg?format=png`

## Example

```php
use Aws\S3\S3Client;
use League\Flysystem\Adapter\AwsS3 as S3Adapter;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem;

// Connect to S3 account
$s3Client = S3Client::factory([
    'key' => 'your-key',
    'secret' => 'your-secret',
]);

// Setup server and define source and cache
$glide = new Glide\Server(
    new Filesystem(new S3Adapter($s3Client, 'bucket-name')),
    new Filesystem(new LocalAdapter('cache-folder'))
);

// Output image based on current URL
$glide->output(
    $request->getPathInfo(),
    $request->query->all()
);

// Output image manually
$glide->output(
    'image.jpg',
    [
        'w' => 300,
        'fit' => 'crop',
    ]
);
```

## Securing Images

If you want additional security on your images, you can add a secure signature so that no one can alter the parameters.

Start by setting a signing key in your Glide server:

```php
// Setup server and define source and cache
$glide = new Glide\Server('images-folder', 'cache-folder');

// Enable secure images by setting a signing key
$glide->setSignKey('your-signing-key');
```

Next, generate a secure token when requesting images from your server. For example, instead of requesting `image.jpg?w=1000`, you would request `image.jpg?w=1000&token=6db10b02a4132a8714b6485d1138fc87` instead. Glide comes with a URL builder to make this process easy.

```php
// Create a instance of the URL builder
$urlBuilder = new Glide\UrlBuilder('http://your-website.com', 'your-sign-key');

// Generate a url
$url = $urlBuilder->getUrl('image.jpg', ['w' => 1000]);

// Use the url in your app
echo '<img src="' . $url . '">';

// Prints out
// <img src="http://your-website.com/image.jpg?w=1000&token=af3dc18fc6bfb2afb521e587c348b904">
```

## Configuration Options

### Driver

By default Glide uses the [GD Library](http://php.net/manual/en/book.image.php). However you can also use Glide with Imagemagick if the [Imagick](http://php.net/manual/en/book.imagick.php) PHP extension is installed.

```php
$glide->setDriver('imagick');
```

### Max Image Size

If you're not securing images with a signing key, you may choose to limit how large images can be generated. The following setting will set the maximum allowed total image size, in pixels.

```php
$glide->setMaxImageSize(2000*2000);
```