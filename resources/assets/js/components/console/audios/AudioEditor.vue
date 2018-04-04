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
                <select name="account" v-model="form_data.someline_category_id" class="form-control m-b">
                    <option value="" disabled>请选择专辑</option>
                    <template v-for="someline_category in someline_categories">
                        <optgroup :label="someline_category.category_name">
                            <template v-for="child_category in someline_category.children">
                                <option :value="child_category.someline_category_id">
                                    {{ child_category.category_name }}
                                </option>
                            </template>
                        </optgroup>
                    </template>
                </select>
            </template>
            <div class="well m-t">
                <p>已上传音频条数：3条</p>
                <p>审核未过：1条</p>
                <p class="m-b-none">最近上传时间： 2017-06-14</p>
            </div>
        </someline-form-group>
        <someline-form-group-line/>

        <someline-form-group>
            <template slot="Label">声音文件</template>
            <template slot="ControlArea">
                <el-upload
                        name="file"
                        accept=".mp3"
                        :data="fileUploadData"
                        :with-credentials="true"
                        :on-success="onFileUploadSuccess"
                        :on-error="onFileUploadError"
                        drag
                        action="/file"
                        multiple>
                    <i class="el-icon-upload"></i>
                    <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
                    <div class="el-upload__tip" slot="tip">支持文件类型：mp3</div>
                </el-upload>
            </template>
            <template v-if="selected_audio">
                <audio class="w-full m-t" :src="selected_audio.someline_file_url" controls autoplay>
                    Sorry, your browser doesn't support HTML5 audio
                </audio>
            </template>
            <table class="table table-responsive table-bordered m-t">
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
                        <td>{{ audio.duration }}</td>
                        <td>未审核</td>
                        <td>
                            <button class="btn btn-default btn-sm">文件替换</button>
                            <button class="btn btn-default btn-sm">修改</button>
                            <button class="btn btn-default btn-sm">删除</button>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
            <pre class="m-t-sm m-b-none">{{ form_data.example_files }}</pre>
        </someline-form-group>
        <someline-form-group-line/>

        <someline-form-group>
            <template slot="ControlArea">
                <button type="submit" class="btn btn-primary">保存</button>
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

                form_data: {
                    book_id: null,
                    name: null,
                    author: null,
                    broadcaster: null,
                    broadcaster_type: "男单播",
                    someline_image_id: null,
                    someline_image_url: null,
                    someline_category_id: "",
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
                someline_categories: [],
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

            this.fetchCategoryData();

        },
        watch: {
            'data_loaded': function () {

                if (this.data_loaded == 1 && this.inEditMode) {
                    this.fetchData();
                } else {
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
                    .get(`audios/${this.itemId}`, {
                        params: {
                            include: 'tags',
                            edit: true,
                        }
                    })
                    .then(this.updateDataFromResponse, this.handleResponseError)
                    .finally(this.handleFormResponseComplete);
            },
            fetchCategoryData() {

//                if (this.isLoading) {
//                    return;
//                }
                this.isLoading = true;

                this.$api
                    .get('categories', {
                        params: {
                            type: 'audio',
                            all_children: true
                        }
                    })
                    .then((response) => {
                        this.someline_categories = response.data.data;
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

                if (this.inEditMode) {
                    this.$api.put(`/audios/${this.form_data.audio_id}`, this.form_data)
                        .then(this.handleUpdatedResponseSuccess, this.handleResponseError)
                        .finally(this.handleFormResponseComplete);
                } else {
                    this.$api.post('audios', this.form_data)
                        .then(this.handleCreatedResponseSuccess, this.handleResponseError)
                        .finally(this.handleFormResponseComplete);
                }

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