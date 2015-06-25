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
using System.Windows.Shapes;

namespace ECWClient
{
    /// <summary>
    /// SelectClassroom.xaml 的交互逻辑
    /// </summary>
    public partial class SelectClassroom : Window
    {
        public SelectClassroom()
        {
            InitializeComponent();
            // judge whether classroom is set
            Microsoft.Win32.RegistryKey key = Microsoft.Win32.Registry.CurrentUser;
            Microsoft.Win32.RegistryKey ecw = key.OpenSubKey("software\\ecw");
            if (ecw != null)
            {
                this.Hide();
                MainWindow mw = new MainWindow((string)ecw.GetValue("classroom"));
                mw.Show();
            }
        }

        private void comboBox1_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            string building = comboBox1.Items[comboBox1.SelectedIndex].ToString();
            comboBox2.Items.Clear();
            if (building == "A栋")
            {
                comboBox2.Items.Add("A101"); comboBox2.Items.Add("A102"); comboBox2.Items.Add("A103");
                comboBox2.Items.Add("A104"); comboBox2.Items.Add("A105"); comboBox2.Items.Add("A201");
                comboBox2.Items.Add("A202"); comboBox2.Items.Add("A203"); comboBox2.Items.Add("A204");
                comboBox2.Items.Add("A205"); comboBox2.Items.Add("A206"); comboBox2.Items.Add("A207");
                comboBox2.Items.Add("A301"); comboBox2.Items.Add("A302"); comboBox2.Items.Add("A303");
                comboBox2.Items.Add("A304"); comboBox2.Items.Add("A305"); comboBox2.Items.Add("A306");
                comboBox2.Items.Add("A401"); comboBox2.Items.Add("A402"); comboBox2.Items.Add("A403");
                comboBox2.Items.Add("A404"); comboBox2.Items.Add("A405"); comboBox2.Items.Add("A501");
                comboBox2.Items.Add("A502"); comboBox2.Items.Add("A503"); comboBox2.Items.Add("A504");
                comboBox2.Items.Add("A505");
            }
            else if (building == "B栋")
            {
                comboBox2.Items.Add("B101"); comboBox2.Items.Add("B102"); comboBox2.Items.Add("B103");
                comboBox2.Items.Add("B104"); comboBox2.Items.Add("B201");
                comboBox2.Items.Add("B202"); comboBox2.Items.Add("B203"); comboBox2.Items.Add("B204");
                comboBox2.Items.Add("B205"); comboBox2.Items.Add("B301"); comboBox2.Items.Add("B302");
                comboBox2.Items.Add("B303"); comboBox2.Items.Add("B304"); comboBox2.Items.Add("B305");
                comboBox2.Items.Add("B401"); comboBox2.Items.Add("B402"); comboBox2.Items.Add("B403");
                comboBox2.Items.Add("B501"); comboBox2.Items.Add("B502"); comboBox2.Items.Add("B503");
            }
            else if (building == "C栋")
            {
                comboBox2.Items.Add("C101"); comboBox2.Items.Add("C102"); comboBox2.Items.Add("C103");
                comboBox2.Items.Add("C104"); comboBox2.Items.Add("C105"); comboBox2.Items.Add("C201");
                comboBox2.Items.Add("C202"); comboBox2.Items.Add("C203"); comboBox2.Items.Add("C204");
                comboBox2.Items.Add("C205"); comboBox2.Items.Add("C206"); comboBox2.Items.Add("C207");
                comboBox2.Items.Add("C301"); comboBox2.Items.Add("C302"); comboBox2.Items.Add("C303");
                comboBox2.Items.Add("C304"); comboBox2.Items.Add("C305"); comboBox2.Items.Add("C306");
                comboBox2.Items.Add("C401"); comboBox2.Items.Add("C402"); comboBox2.Items.Add("C403");
                comboBox2.Items.Add("C404"); comboBox2.Items.Add("C405"); comboBox2.Items.Add("C501");
                comboBox2.Items.Add("C502"); comboBox2.Items.Add("C503"); comboBox2.Items.Add("C504");
                comboBox2.Items.Add("C505");
            }
            else if (building == "D栋")
            {
                comboBox2.Items.Add("D101"); comboBox2.Items.Add("D102"); comboBox2.Items.Add("D103");
                comboBox2.Items.Add("D104"); comboBox2.Items.Add("D201");
                comboBox2.Items.Add("D202"); comboBox2.Items.Add("D203"); comboBox2.Items.Add("D204");
                comboBox2.Items.Add("D205"); comboBox2.Items.Add("D301"); comboBox2.Items.Add("D302");
                comboBox2.Items.Add("D303"); comboBox2.Items.Add("D304"); comboBox2.Items.Add("D305");
                comboBox2.Items.Add("D401"); comboBox2.Items.Add("D402"); comboBox2.Items.Add("D403");
                comboBox2.Items.Add("D501"); comboBox2.Items.Add("D502"); comboBox2.Items.Add("D503");
            }
            else if (building == "E栋")
            {
                comboBox2.Items.Add("E101"); comboBox2.Items.Add("E102"); comboBox2.Items.Add("E103");
                comboBox2.Items.Add("E104"); comboBox2.Items.Add("E105"); comboBox2.Items.Add("E106");
                comboBox2.Items.Add("E201"); comboBox2.Items.Add("E202"); comboBox2.Items.Add("E203");
                comboBox2.Items.Add("E204"); comboBox2.Items.Add("E205");
                comboBox2.Items.Add("E301"); comboBox2.Items.Add("E302"); comboBox2.Items.Add("E303");
                comboBox2.Items.Add("E304"); comboBox2.Items.Add("E305");
                comboBox2.Items.Add("E401"); comboBox2.Items.Add("E402"); comboBox2.Items.Add("E403");
                comboBox2.Items.Add("E404"); comboBox2.Items.Add("E405"); comboBox2.Items.Add("E501");
                comboBox2.Items.Add("E502"); comboBox2.Items.Add("E503"); comboBox2.Items.Add("E504");
                comboBox2.Items.Add("E505");
            }
            comboBox2.SelectedIndex = 0;
        }

        private void Button_Click(object sender, RoutedEventArgs e)
        {
            string classroom = comboBox2.Items[comboBox2.SelectedIndex].ToString();
            Microsoft.Win32.RegistryKey key = Microsoft.Win32.Registry.CurrentUser;
            Microsoft.Win32.RegistryKey ecw = key.CreateSubKey("software\\ecw");
            ecw.SetValue("classroom", classroom);

            this.Hide();
            MainWindow mw = new MainWindow(classroom);
            mw.Show();
        }
    }
}
