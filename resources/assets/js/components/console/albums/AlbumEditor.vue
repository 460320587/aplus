<style scoped>
</style>

<template>

    <someline-form-panel
            @form-submit="onFormSubmit"
            v-loading.body="isLoading"
    >
        <template slot="PanelHeading">
            专辑信息
        </template>

        <someline-form-group>
            <template slot="Label">书号</template>
            <template slot="ControlArea">
                <div class="row">
                    <div class="col-xs-8">
                        <someline-form-control-input
                                placeholder="书号"
                                v-model="form_data.book_id"
                        />
                    </div>
                    <div class="col-xs-4">
                        <button type="button" class="btn btn-default padder-lg">搜索</button>
                    </div>
                </div>
            </template>
        </someline-form-group>
        <someline-form-group-line/>

        <someline-form-group-input
                placeholder="书籍名称"
                :rounded="true"
                v-model="form_data.name"
                :required="true"
        >
            <template slot="Label">书籍名称</template>
        </someline-form-group-input>
        <someline-form-group-line/>

        <someline-form-group-input
                placeholder="作者"
                v-model="form_data.author"
                :required="true"
        >
            <template slot="Label">作者</template>
        </someline-form-group-input>
        <someline-form-group-line/>

        <someline-form-group-input
                placeholder="演播"
                v-model="form_data.broadcaster"
                :required="true"
        >
            <template slot="Label">演播</template>
        </someline-form-group-input>
        <someline-form-group-line/>

        <someline-form-group-radio-list
                name="broadcaster_radio"
                :items="broadcaster_items"
                v-model="form_data.broadcaster_type">
            <template slot="Label">演绎形式</template>
        </someline-form-group-radio-list>
        <someline-form-group-line/>

        <someline-form-group-image-upload v-model="form_data.someline_image_id"
                                          :model-image-url="form_data.someline_image_url"
                                          :is-model-use-id="true"
                                          :limit-size="10000"
                                          :max-image="1"
        >
            <template slot="Label">封面</template>
        </someline-form-group-image-upload>
        <someline-form-group-line/>

        <someline-form-group-text-area
                height="200px"
                v-model="form_data.brief"
        >
            <template slot="Label">内容简介</template>
        </someline-form-group-text-area>
        <someline-form-group-line/>

        <someline-form-group-radio-list
                name="payment_radio"
                :inline="true"
                :items="payment_items"
                v-model="form_data.payment_type">
            <template slot="Label">付费方式</template>
        </someline-form-group-radio-list>
        <someline-form-group-line/>

        <someline-form-group>
            <template slot="Label">付费价格</template>
            <template slot="ControlArea">
                <div class="input-group">
                    <input type="number" class="form-control" placeholder="付费价格"
                           v-model="form_data.payment_amount">
                    <span class="input-group-addon">
                        {{ form_data.payment_type == '1' ? '%' : '元／小时' }}
                    </span>
                </div>
            </template>
        </someline-form-group>
        <someline-form-group-line/>

        <someline-form-group>
            <template slot="Label">分类</template>
            <template slot="ControlArea">
                <select name="account" v-model="form_data.someline_category_id" class="form-control m-b">
                    <option value="" disabled>请选择分类</option>
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
        </someline-form-group>
        <someline-form-group-line/>

        <someline-form-group>
            <template slot="Label">关键字</template>
            <template slot="ControlArea">
                <el-select
                        v-model="form_data.keywords_data"
                        multiple
                        filterable
                        allow-create
                        placeholder="请输入关键词"
                        style="width: 100%">
                </el-select>
            </template>
            <template slot="HelpText">输入关键字，按下选中回车即可添加</template>
        </someline-form-group>
        <someline-form-group-line/>

        <someline-form-group-radio-list
                name="copyright_radio"
                :items="copyright_items"
                v-model="form_data.copyright">
            <template slot="Label">版权方</template>
        </someline-form-group-radio-list>
        <someline-form-group-line/>

        <someline-form-group-radio-list
                name="status_radio"
                :inline="true"
                :items="status_items"
                v-model="form_data.status">
            <template slot="Label">状态</template>
        </someline-form-group-radio-list>
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

                broadcaster_items: [
                    {
                        text: '男单播',
                    },
                    {
                        text: '女单播',
                    },
                    {
                        text: '男女双人',
                    },
                    {
                        text: '多人小说剧',
                    },
                    {
                        text: '广播剧',
                    },
                ],

                copyright_items: [
                    {
                        text: '米赢',
                        checked: true,
                    },
                    {
                        text: '掌阅',
                    },
                ],

                status_items: [
                    {
                        text: '连载中',
                        value: '0',
                        checked: true,
                    },
                    {
                        text: '已完结',
                        value: '1',
                    },
                ],

                payment_items: [
                    {
                        text: '保底分成',
                        value: '1',
                        checked: true,
                    },
                    {
                        text: '录制单价',
                        value: '2',
                    },
                ],

                single_checkbox_items: [
                    {
                        text: '置顶',
                        value: 'yes',
                    }
                ],

                editor: null,

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
                    .get(`albums/${this.itemId}`, {
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
                            type: 'Album',
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
                // this.form_data.someline_image = data.someline_image_url;
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
                    this.$api.put(`/albums/${this.form_data.album_id}`, this.form_data)
                        .then(this.handleUpdatedResponseSuccess, this.handleResponseError)
                        .finally(this.handleFormResponseComplete);
                } else {
                    this.$api.post('albums', this.form_data)
                        .then(this.handleCreatedResponseSuccess, this.handleResponseError)
                        .finally(this.handleFormResponseComplete);
                }

            },
            handleCreatedResponseSuccess(response) {
                this.$message({
                    message: '保存成功',
                    type: 'success'
                });
                this.redirectToUrlFromBaseUrl(`/console/albums/${response.data.data.album_id}`);
            },
            handleUpdatedResponseSuccess(response) {
                this.$message({
                    message: '更新成功',
                    type: 'success'
                });
                this.redirectToUrlFromBaseUrl(`/console/albums/${this.itemId}`);
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
        },
    }
</script>