# Upgrade Guide

## Upgrading To 1.0 From 0.x

To upgrade to version 1.0, you need to add a markdown field to your posts table

```
Schema::table('wink_posts', function (Blueprint $table) {
        $table->boolean('markdown')->default(false);
});
```
