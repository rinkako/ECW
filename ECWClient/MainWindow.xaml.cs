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
using System.IO;

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
        private byte isShare = 0;   // 0-不可点击 1-点击上传 2-点击复位
        // 记录课室
        private string _classroom;
        // 
        SynchronizationContext syncContext = null;

        public MainWindow(string classroom)
        {
            InitializeComponent();
            syncContext = SynchronizationContext.Current;
            // 设置课室
            label_room.Content = "课室： " + classroom;
            _classroom = classroom;
            // 允许拖放
            label_drag.AllowDrop = true;
            // 分享按钮不可点击
            isShare = 0;
            button_share.Source = new BitmapImage(
                new Uri(@"imageAssets/Button_Mainpage_01_unable.png", UriKind.Relative));
        }

        private void ShareCode(object text)
        {
            // 清空上传文件
            ssv.Initialize();
            // 按钮复位
            isShare = 2;
            button_share.Source = new BitmapImage(
                new Uri(@"imageAssets/Button_Mainpage_03.png", UriKind.Relative));
            label_drag.Content = Convert.ToString(text);
            label_drag.Foreground = new SolidColorBrush(Colors.Red);
            label_drag.FontSize = 28;
            hint.Visibility = System.Windows.Visibility.Visible;
        }

        private void Fail(object o)
        {
            // 清空上传文件
            ssv.Initialize();
            // 按钮复位
            isShare = 2;
            button_share.Source = new BitmapImage(
                new Uri(@"imageAssets/Button_Mainpage_03.png", UriKind.Relative));
            label_drag.Content = Convert.ToString(o);
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
                syncContext.Post(ShareCode, res);
            }
            else
            {
                syncContext.Post(Fail, "上传失败");
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
                ssv.SetClassroom(_classroom);
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
            if (isShare == 0) return;

            if (isShare == 2)
            {   // 复位
                isShare = 0;
                label_drag.Foreground = new SolidColorBrush(Color.FromRgb(28, 149, 205));
                label_drag.FontSize = 16;
                label_drag.Content = "拖放文件到此处";
                label_drag.AllowDrop = true;
                button_share.Source = new BitmapImage(
                new Uri(@"imageAssets/Button_Mainpage_01_unable.png", UriKind.Relative));
                hint.Visibility = System.Windows.Visibility.Hidden;
                return;
            }

            // 分享文件, 异步上传
            ShareFiles sf = new ShareFiles(Share);
            sf.BeginInvoke(new AsyncCallback(CallBackShare), null);

            // 关闭分享按钮
            isShare = 0;
            button_share.Source = new BitmapImage(
                new Uri(@"imageAssets/Button_Mainpage_01_unable.png", UriKind.Relative));

            label_drag.Content = "上传至服务器...";

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
            // 判断是否有文件夹
            for (int i = 0; i < files.Length; i++)
            {
                if (Directory.Exists(files.GetValue(i).ToString()))
                {
                    
                    MessageBox.Show("请不要上传文件夹");
                    
                    return;
                }
            }
            // 放置到后台
            ssv.SetFiles(files);
            // 开放分享按钮
            isShare = 1;
            button_share.Source = new BitmapImage(
                new Uri(@"imageAssets/Button_Mainpage_01.png", UriKind.Relative));
            // 关闭拖放功能
            label_drag.AllowDrop = false;
            label_drag.Content = "已拖放了" + Convert.ToString(files.Length) + "个文件" +
                 Environment.NewLine + "右键取消";
        }

        // 学生展示按钮点击事件
        private void Image_MouseDown_2(object sender, MouseButtonEventArgs e)
        {
            // 获取当前时间
            string datetime = DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss");
            // hour-minute-second
            string[] time = (datetime.Split(' ')[1]).Split(':');
            // convert to minutes
            int mins = Convert.ToInt32(time[0]) * 60 + Convert.ToInt32(time[1]);
            // 计算当前时段
            int period = ssv.CalcTimePeriod(mins);
            string postString = "cur_period=" + Convert.ToString(period) + "&classroom=" + _classroom;
            this.Hide();
            PrePage pp = new PrePage(postString);
            pp.ShowDialog();
            this.Show();
        }

        private void label_drag_MouseRightButtonDown(object sender, MouseButtonEventArgs e)
        {
            if (isShare == 0 || isShare == 2) return;
            isShare = 0;
            ssv.Initialize();
            button_share.Source = new BitmapImage(
                new Uri(@"imageAssets/Button_Mainpage_01_unable.png", UriKind.Relative));
            label_drag.Content = "拖放文件到此处";
            label_drag.AllowDrop = true;
        }
    }
}
