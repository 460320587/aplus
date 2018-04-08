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
                <p>已上传音频条数：{{ album.audios_count }}条</p>
                <p>审核未过：{{ album.rejected_audios_count }}条</p>
                <p class="m-b-none">最近上传时间：
                    <template v-if="latest_audio">
                        {{ latest_audio.updated_at }}
                    </template>
                    <template v-else>
                        暂无
                    </template>
                </p>
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
                        <td>{{ audio.name }} <a href="#" @click.prevent="doPlayAudio(audio)"><i
                                class="fa fa-play-circle"></i></a></td>
                        <td>{{ audio.audio_length }}</td>
                        <td>
                            <template v-if="audio.latest_review && audio.status == -1">
                                <a href="#"
                                   @click.prevent="showReviewText(audio.latest_review.data)">{{ audio.status_text }} <i
                                        class="fa fa-info-circle"></i></a>
                            </template>
                            <template v-else>
                                {{ audio.status_text }}
                            </template>
                        </td>
                        <td>
                            <button type="button" class="btn btn-default btn-xs"
                                    v-if="audio.status != 1"
                                    @click="doReplaceAudio(audio)">文件替换
                            </button>
                            <button type="button" class="btn btn-default btn-xs"
                                    v-if="audio.status != 1"
                                    @click="doEditAudio(audio)">修改
                            </button>
                            <button type="button" class="btn btn-default btn-xs"
                                    v-if="audio.status == 0 && !audio.latest_review"
                                    @click="doConfirmDelete(audio)">删除
                            </button>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
            <!--<pre class="m-t-sm m-b-none">{{ form_data.example_files }}</pre>-->
        </someline-form-group>
        <!--<someline-form-group-line/>-->

        <el-dialog title="编辑" v-model="isDialogEditVisible" :visible.sync="isDialogEditVisible">

            <form class="form-horizontal" @submit.prevent="onSubmitEditForm">

                <someline-form-group-input
                        placeholder="声音标题"
                        v-model="edit_form.name"
                        :required="true"
                >
                    <template slot="Label">标题</template>
                    <!--<pre class="m-t-sm m-b-none">{{ add_form.category_name }}</pre>-->
                </someline-form-group-input>
                <someline-form-group-line/>

                <someline-form-group>
                    <template slot="ControlArea">
                        <button type="submit" class="btn btn-primary" :disabled="isEditLoading">更新</button>
                    </template>
                    <!--<pre class="m-t-sm m-b-none">{{ add_form }}</pre>-->
                </someline-form-group>

            </form>

        </el-dialog>

        <el-dialog title="文件替换"
                   v-model="isDialogReplaceVisible" :visible.sync="isDialogReplaceVisible">

            <!--<form class="form-horizontal" @submit.prevent="onSubmitEditForm">-->

            <someline-form-group
                    v-loading.body="isReplaceLoading">
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
                            action="/file">
                        <i class="el-icon-upload"></i>
                        <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
                        <div class="el-upload__tip" slot="tip">支持文件类型：mp3</div>
                    </el-upload>
                </template>
                <!--<pre class="m-t-sm m-b-none">{{ form_data.example_files }}</pre>-->
            </someline-form-group>
            <!--<someline-form-group-line/>-->

            <!--<someline-form-group>-->
            <!--<template slot="ControlArea">-->
            <!--<button type="submit" class="btn btn-primary" :disabled="isLoading">更新</button>-->
            <!--</template>-->
            <!--&lt;!&ndash;<pre class="m-t-sm m-b-none">{{ add_form }}</pre>&ndash;&gt;-->
            <!--</someline-form-group>-->

            <!--</form>-->

        </el-dialog>

        <!--<someline-form-group>-->
        <!--<template slot="ControlArea">-->
        <!--<button type="submit" class="btn btn-primary">保存</button>-->
        <!--</template>-->
        <!--&lt;!&ndash;<pre class="m-t-sm m-b-none">{{ form_data }}</pre>&ndash;&gt;-->
        <!--</someline-form-group>-->

    </someline-form-panel>

</template>

<script>
    export default {
        props: {
            itemId: {
                type: String,
                required: false
            }
        },
        data() {
            return {

                isLoading: false,
                isEditLoading: false,
                isReplaceLoading: false,
                isDialogEditVisible: false,
                isDialogReplaceVisible: false,

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

                edit_form: {
                    name: null,
                },

                selected_audio: null,
                edit_audio: null,

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
            inEditMode() {
                return !!this.itemId;
            },
            album() {
                return this.data;
            },
            audio_files() {
                try {
                    return this.album.audios.data;
                } catch (e) {
                    return [];
                }
            },
            latest_audio() {
                try {
                    return this.album.latest_audio.data;
                } catch (e) {
                    return null;
                }
            },
        },
        components: {},
        mounted() {
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
                            include: 'audios.latest_review,latest_audio',
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
            onFormSubmit() {
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
                this.fetchData();
//                this.redirectToUrlFromBaseUrl(`/console/audios/${response.data.data.audio_id}`);
            },
            handleUpdatedResponseSuccess(response) {
                this.$message({
                    message: '更新成功',
                    type: 'success'
                });
                this.fetchData();
//                this.redirectToUrlFromBaseUrl(`/console/audios/${this.itemId}`);
            },
            handleResponseError(error) {

                var error_message = '请求失败';
//                try {
//                    var response_error_message = error.response.data.message;
//                    if (response_error_message) {
//                        console.error(response_error_message);
//                        error_message = this.$options.filters.truncate(response_error_message, 80);
//                    }
//                } catch (e) {
//                    console.error(e.stack);
//                }

                this.$message({
                    message: error_message,
                    type: 'error'
                });

            },
            handleFormResponseComplete() {

                this.isLoading = false;

            },
            onFileUploadSuccess(response, file, fileList) {
                console.log('response', response);
                fileList.splice(fileList.indexOf(file), 1);
                if (response.data) {

                    var audio_file = response.data;
//                    this.form_data.audio_files.push(response.data)

                    if (!this.edit_audio) {
                        return;
                    }

                    if (audio_file.bitrate < 128) {
                        this.$message({
                            message: '上传失败，码率必须至少128kbps，请重新上传',
                            type: 'error'
                        });
                        return;
                    }

                    this.isReplaceLoading = true;

                    this.$api.put(`audios/${this.edit_audio.audio_id}`, {
                        'someline_file_id': audio_file.someline_file_id,
                        'audio_length': audio_file.duration,
                        'audio_bitrate': audio_file.bitrate,
                        'original_name': audio_file.original_name,
                    })
                        .then((response) => {
                            this.isDialogReplaceVisible = false;
                            this.isReplaceLoading = false;

                            this.handleUpdatedResponseSuccess(response);
                        }, this.handleResponseError)
                        .finally(() => {
                        });

                }
            },
            onFileUploadError(err, file, fileList) {
                console.log('error', err);
                this.$message({
                    message: '上传失败',
                    type: 'error'
                });
            },
            doPlayAudio(audio) {
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
            doDelete(audio) {

                this.isDeleting = true;
                this.$api.delete(`/audios/${audio.audio_id}`, {})
                    .then((response) => {

                        this.$message.success('删除成功');
                        this.fetchData();
//                        this.redirectToUrlFromBaseUrl('/console/audios');

                    }, this.handleResponseError)
                    .finally(() => {
                        this.isDeleting = false;
                    })

            },
            showReviewText(review) {
                this.$alert(review.review_text, '失败原因', {
                    confirmButtonText: '确定'
                });
            },
            doEditAudio(audio) {
                this.edit_audio = audio;
                this.edit_form.name = audio.name;
                this.isDialogEditVisible = true;
                this.isDialogReplaceVisible = false;
            },
            doReplaceAudio(audio) {
                this.edit_audio = audio;
                this.isDialogReplaceVisible = true;
                this.isDialogEditVisible = false;
            },
            onSubmitEditForm() {

                if (!this.edit_audio) {
                    return;
                }

                this.isEditLoading = true;

                this.$api.put(`audios/${this.edit_audio.audio_id}`, this.edit_form)
                    .then((response) => {
                        this.isDialogEditVisible = false;
                        this.handleUpdatedResponseSuccess(response)
                    }, this.handleResponseError)
                    .finally(() => {
                        this.isEditLoading = false;
                    });

            },
        },
    }
</script>