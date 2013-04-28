# Vakuum
An open-source online judge for ACM/ICPC & Olympiad in Informatics.

## Vakuum介绍
Vakuum是一个基于Linux+PHP的在线评测系统（Online Judge），主要面向信息学竞赛（OI）和ACM/ICPC。

## 功能

### 在线评测
用户提交程序代码，在线编译，执行测试数据，对其评判并返回结果。支持自定义检查器(Speciel Judge)。支持特殊类型题目（代码检查、交互式、库式、提交答案式）。

### 比赛举办
兼顾OI和ACM，支持个人赛、团队赛。比赛模式有在线赛（ACM），离线赛（OI），自由计时赛（USACO）。

### 晋级模式
根据现有题库，由管理员设置或用户推荐一组晋级路线，即做完一组题目后才能进入下一组，并获得相应称号。参加USACO和UVA。

### 问题讨论
简洁、高效、可控。

### 其他功能
题库检索，用户排名，队伍系统，权限管理，代码备份，……

## 架构

Vakuum系统分为vakuum-web和vakuum-judge两大模块。vakuum-web是一个在线评测系统界面，需要PHP和MySQL支持，可以建立在Linux或Windows平台下。vakuum-judge是评测系统的核心模块，负责处理用户评测请求，与vakuum-web进行通信。通信模块为PHP，核心由C语言编写，只能运行在Linux(Unix)平台下。

vakuum-web是基于PHP和MySQL的一个B/S应用程序，采用MVC（模型-视图-控制器）架构设计，负责与用户交互和与核心通信。支持于评测机分离或和一，支持多评测机协同作业，不需要daemon和底层权限。用户通过访问vakuum-web查看题目，提交代码，vakuum-web将请求派发给vakuum-judge终端。支持多评测机数据同步，支持测试数据分发管理。主要采用HTTP信道发送命令，FTP信道发送数据。

vakuum-judge通信模块由PHP编写，收到请求以后，使用C语言编写的核心对代码进行编译，并在执行器沙盒中执行，保证系统安全。执行器使用ptrace监控用户程序的系统调用和文件调用，根据需求加以放行或禁止，同时支持内存峰值统计，内存限制，用户时间统计，时间限制，输出文件大小限制。

## 許可

GNU Public License v2
