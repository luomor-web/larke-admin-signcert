<template>
  <div class="app-container">
    <el-card>
        <div slot="header" class="clearfix">
            <span>Eddsa证书</span>
        </div>

        <div class="filter-container">
            <el-button v-waves class="filter-item" type="primary" @click="submit">
                提交创建
            </el-button>
        </div>

        <div class="sign-box">
            <div class="sign-setting-payload">
                <div class="sign-data-tip">
                    <span style="margin-right: 10px;">
                        私钥
                    </span>
                    <el-button v-waves size="mini" @click="handleDownload(response.private_key, $event)">
                        下载私钥
                    </el-button>                    
                </div>
                <div class="sign-data-input">
                    <el-input v-model.trim="response.private_key" type="textarea" rows="6" placeholder="私钥ID" />
                </div>                  
            </div>

            <div class="sign-response-data">
                <div class="sign-data-tip">      
                    <span style="margin-right: 10px;">
                        公钥
                    </span>
                    <el-button v-waves size="mini" @click="handleDownload(response.public_key, $event)">
                        下载公钥
                    </el-button>                        
                </div>
                <div class="sign-data-input">
                    <el-input v-model.trim="response.public_key" type="textarea" rows="6" placeholder="公钥ID" />
                </div>                
            </div>
       </div>

    </el-card>
  </div>
</template>

<script>
import clipboard from '@/utils/clipboard'
import { Base64 } from 'js-base64';
import waves from '@/directive/waves'
import { eddsa, getEddsaDownloadUrl } from '../../api/signCert'

export default {
  name: 'ConfigIndex',
  components: {  },
  directives: { waves },
  filters: {

  },
  data() {
    return {
      response: {
          private_key: '',
          public_key: '',
      },   
    }
  },
  created() {
  },
  methods: {
    handleClipboard(text, event) {
        if (text == '') {
            this.errorTip('请先提交创建后复制')
            return 
        }

        clipboard(text, event)
        this.successTip('复制成功')
    },  
    handleDownload(code) {
      if (code == '') {
        this.$message({
          message: '请选择要下载的证书',
          type: 'error',
          duration: 3 * 1000
        })
        return
      }

      const url = getEddsaDownloadUrl(code)
      window.open(url, '_blank')
    },        
    submit() {
        this.response.private_key = ''
        this.response.public_key = ''
        
        eddsa(this.setting).then(response => {
            this.response.private_key = (response.data.private_key)
            this.response.public_key = (response.data.public_key)

            this.successTip('创建成功')
        })
    }
  }
}
</script>

<style scoped>
.sign-data {
    margin-top: 15px;
} 
.sign-data-tip {
    padding: 10px 0;
    font-size: 14px;
    color: #606266;
}
</style>
