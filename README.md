# Portfolio Web System

### Authoring Blog Posts

Individual blog posts are stored in XML format in the /data directory. To add new blog posts to the system simply store your new posts in the following XML format and place them in the /data directory. The web system will detect all blog posts stored in this directory and render in order from the most recent post to the oldest post.

The following demonstrates the minimum requirements for a single blog post.

```
<?xml version="1.0" encoding="UTF-8"?>
<blog>
	<title></title>
	<author></author>
	<content></content>
	<date></date>
	<time></time>
</blog>
```

Additional optional tags that are not currently being used, but for which we have plans to implement uses, include <excerpt> and `<tag>`, with planned support for multiple `<tag>` entries in the same blog post.
