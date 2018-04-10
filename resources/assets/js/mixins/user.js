export default{
    computed: {
        baseUrl(){
            return window.Someline.baseUrl;
        },
        locale(){
            return window.Someline.locale;
        },
        csrfToken(){
            return window.Laravel.csrfToken;
        },
        currentUserId(){
            return window.Someline.state.user.user_id;
        },
        currentUserRole(){
            return window.Someline.state.user.role;
        },
        currentUserName(){
            return window.Someline.state.user.name;
        },
        authUser(){
            return window.Someline.state.user;
        },
        authUserId(){
            return window.Someline.state.user.user_id;
        },
        authUserName(){
            return window.Someline.state.user.name;
        },
    },
    methods: {
        isAuthUser(user_id){
            return this.authUserId == user_id;
        },
        isCurrentUser(user_id){
            return this.currentUserId == user_id;
        },
        isRole(role){
            return this.currentUserRole == role;
        }
    },
}