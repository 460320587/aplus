<style scoped>
</style>

<template>

    <someline-form-panel
            @form-submit="onFormSubmit"
            v-loading.body="isLoading"
    >
        <template slot="PanelHeading">
            选择专辑
        </template>

        <someline-form-group>
            <template slot="Label">专辑名</template>
            <template slot="ControlArea">
                <select name="account" v-model="album_id" class="form-control m-b">
                    <option value="" disabled>请选择专辑</option>
                    <template v-for="album in albums">
                        <option :value="album.album_id">
                            {{ album.name }}
                        </option>
                    </template>
                </select>
            </template>
        </someline-form-group>

        <someline-form-group>
            <template slot="ControlArea">
                <button type="submit" class="btn btn-primary">下一步</button>
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
            }
        },
        data(){
            return {

                isLoading: false,

                album_id: "",

                form_data: {

                    book_id: null,
                    name: null,
                    author: null,
                    broadcaster: null,
                    broadcaster_type: "男单播",
                    someline_image_id: null,
                    someline_image_url: null,
                    brief: null,
                    payment_type: '1',
                    payment_amount: null,
                    keywords_data: [],
                    copyright: '米赢',
                    status: '0',
                },

                audio_files: [],
                selected_audio: null,

                fileUploadData: {
                    _token: window.Laravel.csrfToken
                },

                data: null,
                data_loaded: 0,
                albums: [],
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

            this.fetchAlbumData();

        },
        watch: {
            'data_loaded': function () {

//                if (this.data_loaded == 1 && this.inEditMode) {
//                    this.fetchData();
//                } else {
                this.isLoading = false;
//                }

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
                    .get(`audios/${this.itemId}`, {
                        params: {
                            include: 'tags',
                            edit: true,
                        }
                    })
                    .then(this.updateDataFromResponse, this.handleResponseError)
                    .finally(this.handleFormResponseComplete);
            },
            fetchAlbumData() {

//                if (this.isLoading) {
//                    return;
//                }
                this.isLoading = true;

                this.$api
                    .get('albums/auth_user', {
                        params: {
//                            type: 'audio',
//                            all_children: true
                        }
                    })
                    .then((response) => {
                        this.albums = response.data.data;
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

                this.redirectToUrlFromBaseUrl(`console/albums/${this.album_id}/audios`);

//                if (this.inEditMode) {
//                    this.$api.put(`/audios/${this.form_data.audio_id}`, this.form_data)
//                        .then(this.handleUpdatedResponseSuccess, this.handleResponseError)
//                        .finally(this.handleFormResponseComplete);
//                } else {
//                    this.$api.post('audios', this.form_data)
//                        .then(this.handleCreatedResponseSuccess, this.handleResponseError)
//                        .finally(this.handleFormResponseComplete);
//                }

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