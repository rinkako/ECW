using System;
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
using System.Windows.Navigation;
using System.Windows.Shapes;
using System.Configuration;
using System.Threading;

namespace ECWClient
{
    /// <summary>
    /// MainWindow.xaml 的交互逻辑
    /// </summary>
    public partial class MainWindow : Window
    {
        // 创建后台服务对象
        ShareService ssv = new ShareService();
        // 声明委托
        public delegate string ShareFiles();
        // 标记分享按钮是否可点击
        bool isShare = false;

        public MainWindow(string classroom)
        {
            InitializeComponent();
            // 设置课室
            label_room.Content = "课室： " + classroom;
            // 允许拖放
            label_drag.AllowDrop = true;
            // 分享按钮不可点击
            isShare = false;
            button_share.Source = new BitmapImage(
                new Uri(@"Button_Mainpage_01_unable.png", UriKind.Relative));
        }

        // 异步回调函数
        private void CallBackShare(IAsyncResult result)
        {
            ShareFiles sf = (ShareFiles)
                ((System.Runtime.Remoting.Messaging.AsyncResult)result).AsyncDelegate;
            // 获取异步操作结果
            string res = sf.EndInvoke(result);
            // 上传成功则显示分享码
            if (res != null)
            {

        }
        }

        // 分享文件
        private string Share()
        {
            string shared_code = null;
            bool success = false;
            if (ssv.GetFileNum() > 0)
            {
                // 设置课室
                string classroom = ((string)label_room.Content);
                ssv.SetClassroom(classroom.Substring(classroom.IndexOf(' ') + 1));
                // 设置时间段
                ssv.SetTimePeriod(DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss"));
                // 上传
                success = ssv.Upload();
            }

            if (success)
            {
                // 获取分享码
                shared_code = ssv.GetSharedCode();
            }

            ssv.Initialize();

            return shared_code;
        }

        // 窗体拖动事件
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

        // 关闭按钮点击事件
        private void Button_close_Click(object sender, RoutedEventArgs e)
        {
            Environment.Exit(0);
        }


        // 分享按钮点击事件
        private void Image_MouseDown(object sender, MouseButtonEventArgs e)
        {
            // 关闭分享按钮
            isShare = false;
            button_share.Source = new BitmapImage(
                new Uri(@"Button_Mainpage_01_unable.png", UriKind.Relative));

            // 分享文件, 异步上传
            ShareFiles sf = new ShareFiles(Share);
            sf.BeginInvoke(new AsyncCallback(CallBackShare), null);

        }

        // 学生展示按钮点击事件
        private void Image_MouseDown_1(object sender, MouseButtonEventArgs e)
        {
            
        }

        // 拖放事件
        private void label_drag_Drop(object sender, DragEventArgs e)
        {
            if (!e.Data.GetDataPresent(DataFormats.FileDrop))
            {
                return;
            }
            // 获取拖放的文件名及路径
            Array files = (System.Array)e.Data.GetData(DataFormats.FileDrop);
            // 放置到后台
            ssv.SetFiles(files);
            // 开放分享按钮
            isShare = true;
            button_share.Source = new BitmapImage(
                new Uri(@"Button_Mainpage_01.png", UriKind.Relative));
            // 关闭拖放功能
            label_drag.AllowDrop = false;
            label_drag.Content = "已拖放了" + Convert.ToString(files.Length) + "个文件";
        }
    }
}
