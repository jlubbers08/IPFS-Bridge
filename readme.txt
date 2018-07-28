=== IPFS Bridge ===
Contributors: jlubbers
Tags: IPFS, Distributed Web
Donate link: https://www.jefflubbers.com/ipfs-wordpress/
Requires at least: 4.0.0
Tested up to: 4.9.5
Requires PHP: 5.4.6
Stable tag: trunk
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

IPFS Bridge provides a link from your wordpress site to the IPFS network.


== Description ==

IPFS Bridge provides versioning of pages as the pages saves. After the page saves it publishes to the specified IPFS node. You're IPFS node acts as a server node as it is destributed across the IPFS network. There is additional control over the IPFS configuration through the IPFS API.

== Installation ==
1. Upload the ipfs-host folder to the /wp-content/plugins/ directory
1. Activate the plugin through the ‘Plugins’ menu in WordPress
1. From the Widgets menu, drag the IPFS Page Link widget to the widget area in which you would like the license to appear. Otherwise, there will be no links dispalyed on your site.
1. From the IPFS Bridge Settings Page, fill in the server address, API port, and Gateway Port, save these settings.
1. Triggure a build of the Site on the IPFS network.

== How to Use ==
Once the plugin is setup and properly configured, every time you save a page a version of the IPFS page will be saved and uploaded to your IPFS node.

== Frequently asked questions ==

= How much does this cost?

The first 1000 pages built after the initial site build are free, after that a license is required to continue building pages and submitting them to your IPFS node.
A license is $20 USD and can be purchase throught https://www.jefflubbers.com/

= What is IPFS? =

IPFS is a peer-to-peer distributed file system that seeks to connect all computing devices with the same system of files. To find out more about IPFS visit, https://www.jefflubbers.com/home/interplanetary-file-system/ or https://ipfs.io

== Screenshots ==
1. Main Settings Panel
2. List of IPFS Peers
3. IPFS Configuration Editor
4. IPFS Page Link
5. IPFS page on the IPFS network

== Changelog ==
Release

== Upgrade Notice ==
