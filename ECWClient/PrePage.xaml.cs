using System;
using System.IO;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Shapes;
using System.Threading;
using System.Net;

namespace ECWClient
{
    /// <summary>
    /// PrePage.xaml 的交互逻辑
    /// </summary>
    public partial class PrePage : Window
    {
        // 文件列表
        Queue<string> _files = new Queue<string>();

        SynchronizationContext syncContext = null;

        private string _postString = null;

        // 声明一个委托
        public delegate bool AsyncDownLoad();

        public PrePage(string postString)
        {
            InitializeComponent();
            _postString = postString;
            MessageBox.Show(_postString);
            syncContext = SynchronizationContext.Current;
            getDownloadFiles();
            showDownloadFIles();
        }

        // 从服务器获取展示文件
        private void getDownloadFiles()
        {
            WebClient client = new WebClient();
            client.Encoding = Encoding.UTF8;
            // 获取展示url
            string url = "http://1.easycw.sinaapp.com/index.php/download/get_pre";
            // 编码
            byte[] postData = Encoding.UTF8.GetBytes(_postString);
            // 添加头部信息
            client.Headers.Add("Content-Type", "application/x-www-form-urlencoded");
            client.Headers.Add("ContentLength", postData.Length.ToString());
            // 获取返回结果
            byte[] respondData = client.UploadData(url, "POST", postData);
            // 返回结果编码
            string result = Encoding.UTF8.GetString(respondData);

            Console.WriteLine(result);

            string[] filepaths = result.Split('|');
            for (int i = 0; i < filepaths.Length; i++)
            {
                _files.Enqueue(filepaths[i]);
            }

        }

        // 显示展示文件
        private void showDownloadFIles()
        {
            if (_files.Count == 0) return;
            textBox_files.Text = "";
            for (int i = 0; i < _files.Count; i++)
            {
                string filepath = _files.ElementAt(i);
                textBox_files.Text += filepath.Substring(filepath.LastIndexOf('/') + 1) + Environment.NewLine;
            }
        }

        private void Window_MouseDown(object sender, MouseButtonEventArgs e)
        {
            if (e.ChangedButton == MouseButton.Left)
            {
                try
                {
                    this.DragMove();
                }
                catch (Exception ex)
                {
                    // HERE PREVENT STOP BY SUB WIN MOUSE
                }
            }
        }

        // 返回，关闭窗体
        private void Image_MouseDown(object sender, MouseButtonEventArgs e)
        {
            this.Close();
        }

        // 下载展示
        private void Image_MouseDown_1(object sender, MouseButtonEventArgs e)
        {
            if (_files.Count > 0)
            {
                AsyncDownLoad ad = new AsyncDownLoad(DownLoad);
                ad.BeginInvoke(new AsyncCallback(CallBackDownload), null);
            }
        }

        private void probarRefresh(object text)
        {
            probar.Value = Convert.ToDouble(text);
        }

        // 下载
        private bool DownLoad()
        {
            // 下载目录
            string downloadPath = Environment.GetFolderPath(Environment.SpecialFolder.DesktopDirectory) + "\\\\";
            int oknum = 0, allnum = _files.Count;
            while (_files.Count > 0)
            {
                // 获取当前下载文件链接
                string filepath = _files.Dequeue();
                // 解析文件名
                string filename = filepath.Substring(filepath.LastIndexOf('/'));
                try
                {
                    using (FileStream fs = new FileStream(downloadPath + filename, FileMode.Create, FileAccess.Write))
                    {
                        // 创建请求
                        HttpWebRequest request = (HttpWebRequest)WebRequest.Create(filepath);
                        // 接受响应
                        HttpWebResponse response = (HttpWebResponse)request.GetResponse();
                        // 输出流
                        Stream responseStream = response.GetResponseStream();
                        // 缓冲字节数组
                        byte[] bufferBytes = new byte[2048];
                        // 
                        int bytesRead = 0;
                        do
                        {
                            bytesRead = responseStream.Read(bufferBytes, 0, bufferBytes.Length);
                            fs.Write(bufferBytes, 0, bytesRead);
                        } while (bytesRead > 0);
                        fs.Flush();
                        fs.Close();
                        syncContext.Post(probarRefresh, (double)++oknum / (double)allnum * 100);
                    }
                }
                catch (Exception e)
                {
                    // 下载出错
                    return false;
                }
            }
            return true;
        }

        // 异步回调
        private void CallBackDownload(IAsyncResult result)
        {
            AsyncDownLoad ad = (AsyncDownLoad)
                ((System.Runtime.Remoting.Messaging.AsyncResult)result).AsyncDelegate;
            // 获取异步操作结果
            bool res = ad.EndInvoke(result);
            // 上传成功则显示分享码
            if (res)
            {
                syncContext.Post(Fail, "下载完成");
            }
            else
            {
                syncContext.Post(Fail, "下载失败");
            }
        }

        // fail
        private void Fail(object o)
        {
            MessageBox.Show(Convert.ToString(o));
        }
    }
}
