# Seed Sharing Assistant

The Seed Sharing Assistant uses Shamir's Secret Sharing to share a BIP39 seed phrase in a secure way.

It is currently work in progress.

It is meant to be used on a dedicated air-gapped device with a small screen such as a Raspberry Pi with integrated 3.5" screen.

![Raspberry Pi with integrated 3.5" screen](https://user-images.githubusercontent.com/8155871/161630184-10949eb7-1907-44a8-81e3-2826865d6089.png)

## TODO

 - Mount `tmp` using tmpfs to avoid writing any data to disk

## Credits

 - Daan Sprenkels's Shamir Secret Sharing implementation : https://github.com/dsprenkels/sss-cli.git
 - wkhtmltopdf : https://github.com/wkhtmltopdf/wkhtmltopdf
