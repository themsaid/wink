export default [
    {path: '/', redirect: '/posts'},

    // Posts...
    {
        path: '/posts',
        name: 'posts',
        component: require('./screens/posts/index')
    },
    {
        path: '/posts/new',
        name: 'post-new',
        component: require('./screens/posts/edit')
    },
    {
        path: '/posts/:id',
        name: 'post-edit',
        component: require('./screens/posts/edit')
    },


    // Categories...
    {
        path: '/tags',
        name: 'tags',
        component: require('./screens/tags/index')
    },

    {
        path: '/tags/new',
        name: 'tag-new',
        component: require('./screens/tags/edit')
    },

    {
        path: '/tags/:id',
        name: 'tag-edit',
        component: require('./screens/tags/edit')
    },


    // Authors...
    {
        path: '/team',
        name: 'team',
        component: require('./screens/team/index')
    },

    {
        path: '/team/new',
        name: 'team-new',
        component: require('./screens/team/edit')
    },

    {
        path: '/team/:id',
        name: 'team-edit',
        component: require('./screens/team/edit')
    },


    // Pages...
    {
        path: '/pages',
        name: 'pages',
        component: require('./screens/pages/index')
    },
    {
        path: '/pages/new',
        name: 'page-new',
        component: require('./screens/pages/edit')
    },
    {
        path: '/pages/:id',
        name: 'page-edit',
        component: require('./screens/pages/edit')
    },


    // Catch All...
    {
        path: '*',
        name: 'catch-all',
        component: require('./screens/404'),
    },
];
