using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Net;
using System.IO;

using ICSharpCode.SharpZipLib.Zip;
using ICSharpCode.SharpZipLib.Core;

namespace ECWClient
{
    class ShareService
    {
        private static string _zipFilePath = System.IO.Path.GetTempPath();
        private static string _hostname = "222.200.172.5/ecwroot";
        private static string _username = "ecwadmin";
        private static string _password = "ecw123456";

        private int _file_num;       // 文件数目
        private string[] _files;     // 文件名及路径数组
        private string _zip_file;    // 打包文件名及路径
        private string _targetDir;   // 上传至ftp目标路径
        private string _classroom;   // 课室
        private int _timePeriod;     // 时间段
        private string _url;         // 上传的url
        private string _upload_filename; // 上传的文件名

        public ShareService()
        {
            Initialize();
        }

        // 初始化
        public void Initialize()
        {
            _file_num = 0;
            _files = null;
            _zip_file = null;
            _classroom = null;
            _timePeriod = 0;
            _targetDir = null;
            _url = null;
            _upload_filename = null;
        }


        // 获取文件
        public void SetFiles(Array arr)
        {
            _file_num = arr.Length;
            _files = new string[_file_num];
            for (int i = 0; i < arr.Length; i++)
            {
                _files[i] = arr.GetValue(i).ToString();
            }
        }

        // 获取课室
        public void SetClassroom(string cr)
        {
            _classroom = cr;
        }

        // 获取时间段
        public void SetTimePeriod(string tp)
        {
            // hour-minute-second
            string[] time = (tp.Split(' ')[1]).Split(':');
            // convert to minutes
            int mins = Convert.ToInt32(time[0]) * 60 + Convert.ToInt32(time[1]);
            // 判断第几节课
            _timePeriod = CalcTimePeriod(mins);
        }

        // 返回文件数目
        public int GetFileNum() { return _file_num; }

        // 计算第几节课
        private int CalcTimePeriod(int mins)
        {
            int tp = 0;
            for (int i = 8 * 60, j = 0; i < 24 * 60; i += 55, j++)
            {
                if (mins < i)
                {
                    tp = j;
                    break;
                }
            }

            return tp;
        }

        // 压缩打包
        private void Zip()
        {
            _zip_file = CreateZipFile(_files);
        }

        // 打包文件
        private string CreateZipFile(string[] files)
        {
            string zipFile = _zipFilePath + DateTime.Now.ToString("yyyy-MM-dd HH-mm-ss") + ".zip";

            try
            {
                using (ZipOutputStream s = new ZipOutputStream(File.Create(zipFile)))
                {
                    s.SetLevel(0);
                    byte[] buffer = new byte[4096];
                    foreach (string file in files)
                    {
                        ZipEntry entry = new ZipEntry(Path.GetFileName(file));
                        entry.DateTime = DateTime.Now;
                        s.PutNextEntry(entry);
                        using (FileStream fs = File.OpenRead(file))
                        {
                            int sourceBytes;
                            do
                            {
                                sourceBytes = fs.Read(buffer, 0, buffer.Length);
                                s.Write(buffer, 0, sourceBytes);
                            } while (sourceBytes > 0);
                        }
                    }
                    s.Finish();
                    s.Close();
                }
            }
            catch (Exception e)
            {
                Console.WriteLine("Exception during processing {0}", e);
            }

            return zipFile;
        }

        // 上传
        public bool Upload()
        {
            bool res = false;
            string upload_file = _files[0];
            if (_file_num > 1)
            {
                Zip();
                upload_file = _zip_file;
            }

            // 目标路径
            _targetDir = "ftproot/" + _classroom[0] + "栋/" + _classroom;
            // 上传至ftp
            res = UploadToFtp(upload_file, _targetDir, _hostname, _username, _password);

            // 删除压缩文件包
            if (_zip_file != null)
                File.Delete(_zip_file);

            return res;
        }

        // 上传至ftp
        private bool UploadToFtp(string file, string targetDir, string hostname, string username, string password)
        {
            FileInfo fileinfo = new FileInfo(file);
            // 目标文件名
            string target = fileinfo.Name;
            if (_file_num == 1)
            {
                int idx = target.LastIndexOf('.');
                if (idx == -1)
                    target += "-" + DateTime.Now.ToString("yyyy-MM-dd HH-mm");
                else
                    target = target.Substring(0, idx) + "-" + DateTime.Now.ToString("yyyy-MM-dd HH-mm") + target.Substring(idx);
            }
            // 上传ftp-url
            _url = "ftp://" + hostname + "/" + targetDir + "/" + target;
            _upload_filename = target;
            // 创建一个ftp对象
            System.Net.FtpWebRequest ftp = GetRequest(_url, username, password);
            // 设置ftp命令
            ftp.Method = System.Net.WebRequestMethods.Ftp.UploadFile;
            // 指定文件传输的数据类型
            ftp.UseBinary = true;
            ftp.UsePassive = true;
            // 告诉ftp文件大小
            ftp.ContentLength = fileinfo.Length;
            // 缓冲大小设置为2KB
            const int bufferSize = 2048;
            byte[] content = new byte[bufferSize];
            int dataRead;
            // 打开一个文件流去读上传的文件
            using (FileStream fs = fileinfo.OpenRead())
            {
                try
                {
                    using (Stream rs = ftp.GetRequestStream())
                    {
                        do
                        {
                            // 每次读文件流的2KB
                            dataRead = fs.Read(content, 0, bufferSize);
                            rs.Write(content, 0, dataRead);
                        } while (dataRead > 0);
                        rs.Close();
                    }
                }
                catch (Exception e)
                {
                    Console.WriteLine(e.Message);
                    fs.Close();
                    return false;
                }
                finally
                {
                    fs.Close();
                }
            }

            return true;
        }

        private static FtpWebRequest GetRequest(string URI, string username, string password)
        {
            //根据服务器信息FtpWebRequest创建类的对象
            FtpWebRequest result = (FtpWebRequest)FtpWebRequest.Create(URI);
            //提供身份验证信息
            result.Credentials = new System.Net.NetworkCredential(username, password);
            //设置请求完成之后是否保持到FTP服务器的控制连接，默认值为true
            result.KeepAlive = false;
            return result;
        }

        public string GetSharedCode()
        {
            string shared_code = null;
            // 获取分享码
            WebClient client = new WebClient();
            //client.Encoding = Encoding.UTF8;
            string address = "http://1.easycw.sinaapp.com/index.php/code_test";
            shared_code = client.DownloadString(address);

            // 提交信息到Web服务器
            client = new WebClient();
            // 提交的内容
            string postString = "cwcode=" + shared_code +
                                "&classroom=" + _classroom +
                                "&cur_period=" + Convert.ToString(_timePeriod) +
                                "&uri=" + _url +
                                "&cwname=" + _upload_filename;
            // 编码
            byte[] postData = Encoding.UTF8.GetBytes(postString);
            // post url
            string url = "http://easycw.sinaapp.com/index.php/upload/client_upload";
            client.Headers.Add("Content-Type", "application/x-www-form-urlencoded");
            client.Headers.Add("ContentLength", postData.Length.ToString());
            byte[] respondData = client.UploadData(url, "POST", postData);

            string result = Encoding.UTF8.GetString(respondData);

            Console.WriteLine(result);

            return shared_code;
        }
    }
}
