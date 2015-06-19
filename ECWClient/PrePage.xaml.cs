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
      
        // 声明一个委托
        public delegate void AsyncDownLoad();

        public PrePage()
        {
            InitializeComponent();
            _files.Enqueue("http://easycw-ecw.stor.sinaapp.com/pre_upload_files/url1.txt");
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

        // 下载
        private void DownLoad()
        {
            // 下载目录
            string downloadPath = Environment.GetFolderPath(Environment.SpecialFolder.DesktopDirectory) + "\\\\";

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
                    }
                }
                catch (Exception e)
                {
                    // 下载出错
                }
            }
        }

        // 异步回调
        private void CallBackDownload(IAsyncResult result)
        {

        }

        private void Image_MouseDown_2(object sender, MouseButtonEventArgs e)
        {
            Environment.Exit(0);
        }
    }
}
