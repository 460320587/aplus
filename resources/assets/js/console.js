/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import ElementUI from 'element-ui';
Vue.use(ElementUI);

import SomelineUI from 'someline-ui';
Vue.use(SomelineUI);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);

Vue.component('example', require('./components/Example.vue'));
Vue.component('sl-oauth', require('./components/console/OAuth.vue'));
Vue.component('sl-someline-form-example', require('./components/someline/SomelineFormExample.vue'));

Vue.component('sl-user-list', require('./components/console/users/UserList.vue'));
Vue.component('sl-user-editor', require('./components/console/users/UserEditor.vue'));
Vue.component('sl-user-detail', require('./components/console/users/UserDetail.vue'));

Vue.component('sl-album-list', require('./components/console/albums/AlbumList.vue'));
Vue.component('sl-album-editor', require('./components/console/albums/AlbumEditor.vue'));
Vue.component('sl-album-detail', require('./components/console/albums/AlbumDetail.vue'));
Vue.component('sl-album-category', require('./components/console/albums/AlbumCategory.vue'));
Vue.component('sl-album-assign', require('./components/console/albums/AlbumAssign.vue'));

Vue.component('sl-audio-list', require('./components/console/audios/AudioList.vue'));
Vue.component('sl-audio-new', require('./components/console/audios/AudioNew.vue'));
Vue.component('sl-audio-editor', require('./components/console/audios/AudioEditor.vue'));
Vue.component('sl-audio-upload', require('./components/console/audios/AudioUpload.vue'));
Vue.component('sl-audio-detail', require('./components/console/audios/AudioDetail.vue'));
Vue.component('sl-audio-category', require('./components/console/audios/AudioCategory.vue'));

// Vuex
// const vuexStore = new Vuex.Store({
//     state: {
//         platform: 'console',
//         count: 0
//     },
//     mutations: {
//         increment (state) {
//             state.count++
//         }
//     }
// });
// window.vuexStore = vuexStore;

const app = new Vue({
    el: '#app',
    data: {
        msg: "hello",
    },
    computed: {},
    watch: {},
    events: {},
    created(){
        console.log('Bootstrap.');
        this.initLocale();
    },
    mounted(){
        console.log('Ready.');
    },
    methods: {
        initLocale(){
            console.log('Init Locale.');

            var that = this;
            var lang = this.locale;

            Vue.config.lang = lang;
            Vue.locale(lang, window.Someline.locales);

        },
    }
});
