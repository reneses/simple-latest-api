# simple-latest-api
Wordpress plugin: simple endpoint to retrieve the latest posts in JSON format. The most common use case might be for creating a 'View more' or 'Infinite Scroll' functionality.

- **Contributors**: reneses
- **Tags**: API, latest, JSON
- **Requires at least**: 4.0
- **Tested up to**: 4.5.2

Simple endpoint to retrieve the latest posts in JSON format, accessible at: `/api-latest/{per_page}/{page}`.

## Installation 
**IMPORTANT**: Once installed via the Wordpress Plugins panel, you have to flush and regenerate the rewrite rules database, by going to \'Settings -> Permalinks\' and just clicking \'Save Changes\'

## Why not 'WP REST API'?
There are three reasons these plugin was developed, instead of using the popular [WP REST API](http://v2.wp-api.org):

1. **WP REST API** has a [documented problem with](https://wordpress.org/support/topic/some-media-returns-403-forbidden) post images and permissions: if the post image was originally uploaded in an unpublished/private post, it will not be returned
2. Although **WP REST API** uses the concept of [linking](http://v2.wp-api.org/extending/linking/), reducing the number of needed requests, some specific use cases required several calls to the API
3. **WP REST API** offers a complete API. This interfaces several endpoints that are not needed if our only purpose is to retrieve the last posts; which are only potential vulnerabilities and origin of security concerns.
