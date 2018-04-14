<style scoped>
</style>

<template>

    <someline-form-panel
            @form-submit="onFormSubmit"
            v-loading.body="isLoading"
    >
        <template slot="PanelHeading">
            分配专辑
        </template>

        <template v-if="inEditMode">
            <someline-form-group>
                <template slot="Label">专辑名</template>
                <template slot="ControlArea">
                    <div class="h4 m-t-xs">{{ album.name }}</div>
                </template>
            </someline-form-group>
            <someline-form-group-line/>
        </template>

        <template v-else>
            <someline-form-group>
                <template slot="Label">专辑</template>
                <template slot="ControlArea">
                    <select name="account" v-model="album_id" class="form-control">
                        <option value="" disabled>请选择专辑</option>
                        <template v-for="album in albums">
                            <option :value="album.album_id">
                                {{ album.name }}
                                <template v-if="album.related_user">
                                    (已分配：{{ album.related_user.data.name }})
                                </template>
                            </option>
                        </template>
                    </select>
                </template>
            </someline-form-group>
            <someline-form-group-line/>
        </template>

        <someline-form-group>
            <template slot="Label">分配用户</template>
            <template slot="ControlArea">
                <!--<select name="account" v-model="form_data.related_user_id" class="form-control m-b">-->
                <!--<option value="" disabled>请选择分配的用户</option>-->
                <!--<template v-for="user in users">-->
                <!--<option :value="user.user_id">-->
                <!--{{ user.name }}-->
                <!--</option>-->
                <!--</template>-->
                <!--</select>-->
                <el-select v-model="form_data.related_user_id" style="width: 100%" clearable filterable placeholder="请选择分配的用户">
                    <el-option
                            v-for="item in users"
                            :key="item.user_id"
                            :label="item.name"
                            :value="item.user_id">
                    </el-option>
                </el-select>
            </template>
        </someline-form-group>
        <someline-form-group-line/>

        <someline-form-group>
            <template slot="ControlArea">
                <button type="submit" class="btn btn-primary">分配</button>
            </template>
            <!--<pre class="m-t-sm m-b-none">{{ form_data }}</pre>-->
        </someline-form-group>

    </someline-form-panel>

</template>

<script>
    export default{
        props: {
            itemId: {
                type: String,
                required: false
            },
        },
        data(){
            return {

                isLoading: false,

                album_id: "",

                album: {},
                form_data: {
                    related_user_id: "",
                },

                data: null,
                data_loaded: 0,
                albums: [],
                users: [],
                remoteTagsLoading: false,

            }
        },
        computed: {
            inEditMode(){
                return !!this.itemId;
            }
        },
        components: {},
        mounted(){
            console.log('Component Ready.');

            if (this.inEditMode) {
                this.album_id = this.itemId;
                this.fetchData();
            } else {
                this.fetchAlbumData();
            }

            this.fetchUserData();

        },
        watch: {
            'data_loaded': function () {

                if (this.data_loaded >= 2) {
                    this.isLoading = false;
                }

            }
        },
        events: {},
        methods: {
            fetchData() {
                console.log('fetchData');

//                if (this.isLoading) {
//                    return;
//                }
                this.isLoading = true;

                this.$api
                    .get(`albums/${this.itemId}`, {
                        params: {
                            include: 'audios.latest_review,latest_audio',
//                            edit: true,
                        }
                    })
                    .then((response) => {
                        this.album = response.data.data;
                        this.data_loaded += 1;

                    }, this.handleResponseError)
            },
            fetchAlbumData() {

//                if (this.isLoading) {
//                    return;
//                }
                this.isLoading = true;

                this.$api
                    .get('albums/all', {
                        params: {
                            include: 'related_user',
//                            type: 'audio',
//                            all_children: true
                        }
                    })
                    .then((response) => {
                        this.albums = response.data.data;
                        this.data_loaded += 1;
                    }, this.handleResponseError)

            },
            fetchUserData() {

//                if (this.isLoading) {
//                    return;
//                }
                this.isLoading = true;

                this.$api
                    .get('users/all', {
                        params: {
//                            type: 'audio',
//                            all_children: true
                        }
                    })
                    .then((response) => {
                        this.users = response.data.data;
                        this.data_loaded += 1;
                    }, this.handleResponseError)

            },
            updateDataFromResponse(response){
                let data = response.data.data;

                this.form_data = Object.assign({}, this.form_data, data);
                this.form_data.someline_image = data.someline_image_url;
                this.form_data.someline_category_id = data.someline_category_id;

            },
            arrayColumn(array = [], column) {
                let result = [];
                array.forEach(function (item) {
                    result.push(item[column]);
                });
                return result;
            },
            onFormSubmit(){
                console.log('onFormSubmit');

                this.isLoading = true;

                this.$api.put(`/albums/${this.album_id}/simple`, this.form_data)
                    .then(this.handleCreatedResponseSuccess, this.handleResponseError)
                    .finally(this.handleFormResponseComplete);

            },
            handleCreatedResponseSuccess(response) {
                this.$message({
                    message: '分配成功',
                    type: 'success'
                });
                this.redirectToUrlFromBaseUrl(`/console/albums/assign`);
            },
            handleUpdatedResponseSuccess(response) {
                this.$message({
                    message: '更新成功',
                    type: 'success'
                });
                this.redirectToUrlFromBaseUrl(`/console/audios/${this.itemId}`);
            },
            handleResponseError(error){

                var error_message = '请求失败';
                try {
                    var response_error_message = error.response.data.message;
                    if (response_error_message) {
                        console.error(response_error_message);
                        error_message = this.$options.filters.truncate(response_error_message, 80);
                    }
                } catch (e) {
                    console.error(e.stack);
                }

                this.$message({
                    message: error_message,
                    type: 'error'
                });

            },
            handleFormResponseComplete(){

                this.isLoading = false;

            },
            onFileUploadSuccess(response, file, fileList){
                console.log('response', response);
                if (response.data) {
                    this.audio_files.push(response.data)
                }
            },
            onFileUploadError(err, file, fileList){
                console.log('error', err);
                this.$message({
                    message: '上传失败',
                    type: 'error'
                });
            },
            doPlayAudio(audio){
                console.log('doPlayAudio', audio);
                this.selected_audio = audio;
            }
        },
    }
</script>