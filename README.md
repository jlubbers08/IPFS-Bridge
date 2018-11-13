# IPFS-Bridge

=== IPFS Bridge ===
Contributors: jlubbers
Tags: IPFS, Distributed Web
Donate link: https://www.jefflubbers.com/ipfs-wordpress/
Requires at least: 4.0.0
Tested up to: 4.9.5
Requires PHP: 5.4.6
Stable tag: trunk
License: MIT

IPFS Bridge provides a link from your wordpress site to the IPFS network.

This is a beta plugin and is still under heavy development as IPFS is still in the alpha stages.
# == Description ==

IPFS Bridge provides versioning of pages as the pages saves. After the page saves it publishes to the specified IPFS node. You're IPFS node acts as a server node as it is destributed across the IPFS network. There is additional control over the IPFS configuration through the IPFS API.

# == Installation ==
1. Upload the ipfs-host folder to the /wp-content/plugins/ directory
1. Activate the plugin through the ‘Plugins’ menu in WordPress
1. From the Widgets menu, drag the IPFS Page Link widget to the widget area in which you would like the license to appear. Otherwise, there will be no links dispalyed on your site.
1. From the IPFS Bridge Settings Page, fill in the server address, API port, and Gateway Port, save these settings.
1. Triggure a build of the Site on the IPFS network.

# == How to Use ==
Once the plugin is setup and properly configured, every time you save a page a version of the IPFS page will be saved and uploaded to your IPFS node.
