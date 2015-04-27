using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using System.Net;
using System.Web;
using System.IO;

namespace WindowsFormsApplication1
{
    public partial class Form1 : Form
    {

        public Form1()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            FileInfo fio = new FileInfo("C:\\Users\\佳\\Desktop\\ggg.txt");
            FileWebRequest reqFTP;
            System.Net.ICredentials ic = new System.Net.NetworkCredential("a205", "a205");
            Uri uriii = new Uri("\\\\222.200.172.5\\ftproot\\A栋\\A101\\ourtest\\" + "ggg.txt");
            reqFTP = (FileWebRequest)FileWebRequest.Create(uriii);
            String url_prefix = "http://easycw.sinaapp.com/index.php/mytest/getOurUri/";
            try {

                int x;

                reqFTP.Method = "POST";
                reqFTP.ContentLength = fio.Length;
                reqFTP.Credentials = ic;
                reqFTP.Timeout = 300000;

                FileStream fs = fio.Open(FileMode.Open, FileAccess.Read, FileShare.ReadWrite);

                Stream strm = reqFTP.GetRequestStream();
                string ggg = uriii.ToString();
                ggg = ggg.Replace("/", ">");
                ggg = ggg.Replace("file:", "");
                HttpWebRequest wb = (HttpWebRequest)HttpWebRequest.Create(url_prefix + ggg);
                HttpWebResponse webreponse = (HttpWebResponse)wb.GetResponse();


                int buffLength = 2048;
                byte[] buff = new byte[buffLength];

                int contentLen;

                contentLen = fs.Read(buff, 0, buffLength);

                while (contentLen != 0) {
                    strm.Write(buff, 0, contentLen);
                    contentLen = fs.Read(buff, 0, buffLength);
                }

                strm.Close();
                fs.Close();
                
            }
            catch (Exception ex) {
                reqFTP.Abort();
            }
        }
    }
}
