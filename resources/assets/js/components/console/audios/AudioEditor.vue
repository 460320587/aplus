<style scoped>
</style>

<template>

    <someline-form-panel
            @form-submit="onFormSubmit"
            v-loading.body="isLoading"
    >
        <template slot="PanelHeading">
            声音信息
        </template>

        <someline-form-group>
            <template slot="Label">专辑名</template>
            <template slot="ControlArea">
                <div class="h4 m-t-xs">{{ album.name }}</div>
            </template>

            <div class="well m-t">
                <p>已上传音频条数：{{ audio_files.length }}条</p>
                <p>审核未过：1条</p>
                <p class="m-b-none">最近上传时间： 2017-06-14</p>
            </div>

        </someline-form-group>
        <someline-form-group-line/>

        <someline-form-group>
            <template slot="Label">声音文件</template>
            <template slot="ControlArea">
                <template v-if="selected_audio">
                    <audio class="w-full m-b" :src="selected_audio.someline_file_url" controls autoplay>
                        Sorry, your browser doesn't support HTML5 audio
                    </audio>
                </template>
            </template>
            <table class="table table-responsive table-bordered">
                <thead>
                <tr>
                    <th>文件名称</th>
                    <th>时长</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <template v-for="audio in audio_files">
                    <tr>
                        <td>{{ audio.original_name }} <a href="#" @click.prevent="doPlayAudio(audio)"><i
                                class="fa fa-play"></i></a></td>
                        <td>{{ audio.audio_length }}</td>
                        <td>{{ audio.status_text }}</td>
                        <td>
                            <!--<button type="button" class="btn btn-default btn-xs">文件替换</button>-->
                            <!--<button type="button" class="btn btn-default btn-xs">修改</button>-->
                            <button type="button" class="btn btn-default btn-xs" @click="doConfirmDelete(audio)">删除</button>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
            <!--<pre class="m-t-sm m-b-none">{{ form_data.example_files }}</pre>-->
        </someline-form-group>
        <!--<someline-form-group-line/>-->

        <!--<someline-form-group>-->
        <!--<template slot="ControlArea">-->
        <!--<button type="submit" class="btn btn-primary">保存</button>-->
        <!--</template>-->
        <!--&lt;!&ndash;<pre class="m-t-sm m-b-none">{{ form_data }}</pre>&ndash;&gt;-->
        <!--</someline-form-group>-->

    </someline-form-panel>

</template>

<script>
    export default{
        props: {
            itemId: {
                type: String,
                required: false
            }
        },
        data(){
            return {

                isLoading: false,

                form_data: {
                    audio_files: [],

//                    album_id: "",
//                    book_id: null,
//                    name: null,
//                    author: null,
//                    broadcaster: null,
//                    broadcaster_type: "男单播",
//                    someline_image_id: null,
//                    someline_image_url: null,
//                    brief: null,
//                    payment_type: '1',
//                    payment_amount: null,
//                    keywords_data: [],
//                    copyright: '米赢',
//                    status: '0',
                },

                selected_audio: null,

                fileUploadData: {
                    _token: window.Laravel.csrfToken
                },

                data: {},
                data_loaded: 0,
                albums: [],
                remoteTagsLoading: false,

            }
        },
        computed: {
            inEditMode(){
                return !!this.itemId;
            },
            album(){
                return this.data;
            },
            audio_files(){
                try {
                    return this.album.audios.data;
                } catch (e) {
                    return [];
                }
            },
        },
        components: {},
        mounted(){
            console.log('Component Ready.');

            if (this.inEditMode) {
                this.fetchData();
            }

        },
        watch: {
            'data_loaded': function () {

            }
        },
        events: {},
        methods: {
            fetchData() {
                console.log('fetchData');

                if (this.isLoading) {
                    return;
                }
                this.isLoading = true;

                this.$api
                    .get(`albums/${this.itemId}`, {
                        params: {
                            include: 'audios',
//                            edit: true,
                        }
                    })
                    .then((response) => {
                        this.data = response.data.data;

                    }, this.handleResponseError)
                    .finally(this.handleFormResponseComplete);
            },
//            fetchAlbumData() {
//
////                if (this.isLoading) {
////                    return;
////                }
//                this.isLoading = true;
//
//                this.$api
//                    .get('albums/auth_user', {
//                        params: {
////                            type: 'audio',
////                            all_children: true
//                        }
//                    })
//                    .then((response) => {
//                        this.albums = response.data.data;
//                        this.data_loaded += 1;
//                    }, this.handleResponseError)
//
//            },
//            updateDataFromResponse(response){
//                let data = response.data.data;
//
//                this.form_data = Object.assign({}, this.form_data, data);
//                this.form_data.someline_image = data.someline_image_url;
//                this.form_data.someline_category_id = data.someline_category_id;
//
//            },
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

                this.$api.post(`albums/${this.itemId}/audios`, this.form_data)
                    .then(this.handleCreatedResponseSuccess, this.handleResponseError)
                    .finally(this.handleFormResponseComplete);

            },
            handleCreatedResponseSuccess(response) {
                this.$message({
                    message: '保存成功',
                    type: 'success'
                });
                this.redirectToUrlFromBaseUrl(`/console/audios/${response.data.data.audio_id}`);
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
                    this.form_data.audio_files.push(response.data)
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
            },
            doConfirmDelete(audio) {
                this.$confirm('此操作将永久删除该声音, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'error'
                }).then(() => {

                    this.doDelete(audio);

                })
            },
            doDelete(audio){

                this.isDeleting = true;
                this.$api.delete(`/audios/${audio.audio_id}`, {})
                    .then((response) => {

                        this.$message.success('删除成功');
                        this.reloadPage();
//                        this.redirectToUrlFromBaseUrl('/console/audios');

                    }, this.handleResponseError)
                    .finally(() => {
                        this.isDeleting = false;
                    })

            }
        },
    }
</script>